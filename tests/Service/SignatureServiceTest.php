<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2021 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Tests\Service;

use BenTools\CartesianProduct\CartesianProduct;
use Generator;
use Laminas\Math\Rand;
use ParagonIE\ConstantTime\Base64UrlSafe;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Algorithm\AlgorithmManager;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512;
use RM\Standard\Jwt\Exception\AlgorithmNotFoundException;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Handler\ExpirationClaimHandler;
use RM\Standard\Jwt\Handler\IdentifierClaimHandler;
use RM\Standard\Jwt\Handler\IssuedAtClaimHandler;
use RM\Standard\Jwt\Handler\IssuerClaimHandler;
use RM\Standard\Jwt\Handler\NotBeforeClaimHandler;
use RM\Standard\Jwt\Handler\TokenHandlerList;
use RM\Standard\Jwt\Identifier\RandomUuidGenerator;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\OctetKey;
use RM\Standard\Jwt\Service\SignatureService;
use RM\Standard\Jwt\Storage\RedisTokenStorage;
use RM\Standard\Jwt\Token\Payload;
use RM\Standard\Jwt\Token\SignatureToken;
use RM\Standard\Jwt\Token\TokenInterface;

class SignatureServiceTest extends TestCase
{
    public function testCreation(): SignatureService
    {
        $algorithmManager = new AlgorithmManager($this->getAlgorithms());
        $handlerList = $this->createTokenHandlerList();
        $signatureService = new SignatureService($algorithmManager, $handlerList);

        $this->assertInstanceOf(SignatureService::class, $signatureService);
        $this->assertInstanceOf(AlgorithmManager::class, $signatureService->getAlgorithmManager());
        $this->assertEquals($algorithmManager, $signatureService->getAlgorithmManager());

        return $signatureService;
    }

    public function createTokenHandlerList(): TokenHandlerList
    {
        $host = $_ENV['REDIS_HOST'];
        $port = $_ENV['REDIS_PORT'];

        $generator = new RandomUuidGenerator();
        $tokenStorage = RedisTokenStorage::createFromParameters($host, $port);

        $identifierClaimHandler = new IdentifierClaimHandler($generator, $tokenStorage);

        return new TokenHandlerList(
            [
                new IssuerClaimHandler('test'),
                new ExpirationClaimHandler(),
                new NotBeforeClaimHandler(),
                new IssuedAtClaimHandler(),
                $identifierClaimHandler
            ]
        );
    }

    /**
     * @depends      testCreation
     * @dataProvider provideKeyAndAlgorithm
     *
     * @param AlgorithmInterface $algorithm
     * @param KeyInterface       $key
     * @param SignatureService   $service
     *
     * @return TokenInterface
     * @throws InvalidTokenException
     */
    public function testSign(
        AlgorithmInterface $algorithm,
        KeyInterface $key,
        SignatureService $service
    ): TokenInterface {
        $token = SignatureToken::createWithAlgorithm($algorithm);

        $signedToken = $service->sign($token, $key);
        $this->assertInstanceOf(TokenInterface::class, $signedToken);
        $this->assertTrue($signedToken->isSigned());
        $this->assertFalse($token->isSigned());

        $this->assertTrue($signedToken->getPayload()->containsKey(Payload::CLAIM_ISSUER));
        $this->assertEquals('test', $signedToken->getPayload()->get(Payload::CLAIM_ISSUER));
        $this->assertTrue($signedToken->getPayload()->containsKey(Payload::CLAIM_EXPIRATION));
        $this->assertTrue($signedToken->getPayload()->containsKey(Payload::CLAIM_ISSUED_AT));
        $this->assertTrue($signedToken->getPayload()->containsKey(Payload::CLAIM_NOT_BEFORE));
        $this->assertTrue($signedToken->getPayload()->containsKey(Payload::CLAIM_IDENTIFIER));

        $this->assertFalse($token->getPayload()->containsKey(Payload::CLAIM_ISSUER));
        $this->assertFalse($token->getPayload()->containsKey(Payload::CLAIM_EXPIRATION));
        $this->assertFalse($token->getPayload()->containsKey(Payload::CLAIM_ISSUED_AT));
        $this->assertFalse($token->getPayload()->containsKey(Payload::CLAIM_NOT_BEFORE));
        $this->assertFalse($token->getPayload()->containsKey(Payload::CLAIM_IDENTIFIER));

        $this->assertTrue($service->verify($signedToken, $key));
        $this->assertFalse($service->verify($signedToken->setSignature(null), $key));

        $this->expectException(AlgorithmNotFoundException::class);
        $this->assertFalse($service->verify($signedToken->setAlgorithm(new HS512())->setSignature($signedToken->getSignature()), $key));

        $this->expectException(InvalidTokenException::class);
        $this->assertFalse($service->verify($token, $key));

        return $signedToken;
    }

    public function provideKeyAndAlgorithm(): Generator
    {
        $cartesian = new CartesianProduct([$this->getAlgorithms(), iterator_to_array($this->getKey())]);
        foreach ($cartesian->getIterator() as $arguments) {
            yield $arguments;
        }
    }

    public function getAlgorithms(): array
    {
        return [
            new HS256(),
            new HS3256()
        ];
    }

    public function getKey(): Generator
    {
        yield new OctetKey(Base64UrlSafe::encode(Rand::getBytes(64)));
    }
}
