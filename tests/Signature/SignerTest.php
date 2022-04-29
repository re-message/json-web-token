<?php
/*
 * This file is a part of Re Message Json Web Token implementation.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/json-web-token
 * @link      https://dev.remessage.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Re Message
 * @author    Oleg Kozlov <h1karo@remessage.ru>
 * @license   Apache License 2.0
 * @license   https://legal.remessage.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Tests\Signature;

use BenTools\CartesianProduct\CartesianProduct;
use Generator;
use Laminas\Math\Rand;
use ParagonIE\ConstantTime\Base64UrlSafe;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512;
use RM\Standard\Jwt\Algorithm\Signature\None;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\OctetKey;
use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Property\Header\KeyId;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\Signer;

/**
 * @covers \RM\Standard\Jwt\Signature\Signer
 *
 * @internal
 */
class SignerTest extends TestCase
{
    /**
     * @dataProvider provideKeyAndAlgorithm
     */
    public function testSign(AlgorithmInterface $algorithm, KeyInterface $key): SignatureToken
    {
        $token = SignatureToken::createWithAlgorithm($algorithm);

        $signer = new Signer();
        $signed = $signer->sign($token, $algorithm, $key);

        self::assertNotSame($signed, $token);
        self::assertFalse($token->isSigned());
        self::assertNull($token->getSignature());
        self::assertTrue($signed->isSigned());
        self::assertNotNull($signed->getSignature());

        return $signed;
    }

    /**
     * @dataProvider provideKeyAndAlgorithm
     */
    public function testVerify(AlgorithmInterface $algorithm, KeyInterface $key): void
    {
        $token = SignatureToken::createWithAlgorithm($algorithm);
        $signer = new Signer();
        $signed = $signer->sign($token, $algorithm, $key);

        $otherAlgorithm = new None();
        $otherKey = $this->generateOctetKey();

        self::assertTrue($signer->verify($signed, $algorithm, $key));
        self::assertFalse($signer->verify($signed, $otherAlgorithm, $key));
        self::assertFalse($signer->verify($signed, $algorithm, $otherKey));
        self::assertFalse($signer->verify($signed, $otherAlgorithm, $otherKey));
    }

    public function testVerifyUnsignedToken(): void
    {
        $algorithm = new HS256();
        $key = $this->generateOctetKey();

        $token = SignatureToken::createWithAlgorithm($algorithm);
        $signer = new Signer();

        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionMessage('no signature');
        $signer->verify($token, $algorithm, $key);
    }

    public function testSignAlreadySignedToken(): void
    {
        $algorithm = new HS3256();
        $key = $this->generateOctetKey();
        $token = SignatureToken::createWithAlgorithm($algorithm);

        $signer = new Signer();
        $signed = $signer->sign($token, $algorithm, $key);

        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionMessage('signed');
        $signer->sign($signed, $algorithm, $key);
    }

    public function provideKeyAndAlgorithm(): iterable
    {
        $cartesian = new CartesianProduct(
            [
                iterator_to_array($this->getAlgorithms()),
                iterator_to_array($this->getKeys()),
            ]
        );

        /**
         * @var AlgorithmInterface $algorithm
         * @var KeyInterface       $key
         */
        foreach ($cartesian->getIterator() as [$algorithm, $key]) {
            $name = sprintf('alg %s and key %s', $algorithm->name(), $key->getType());

            yield $name => [$algorithm, $key];
        }
    }

    public function getAlgorithms(): Generator
    {
        yield new HS256();

        yield new HS3256();
    }

    public function getKeys(): Generator
    {
        yield $this->generateOctetKey();
    }

    /**
     * @dataProvider provideToken
     */
    public function testSignUpdatesAlgorithmAndKey(SignatureToken $token): void
    {
        $algorithm = new HS3256();
        $key = $this->generateOctetKey();

        $signer = new Signer();
        $signed = $signer->sign($token, $algorithm, $key);

        $algorithmParameter = $signed->getHeader()->get(Algorithm::NAME);
        self::assertSame($algorithm->name(), $algorithmParameter->getValue());

        $keyIdParameter = $signed->getHeader()->get(KeyId::NAME);
        $expectedKeyId = $key->get(KeyInterface::PARAM_IDENTIFIER);
        self::assertSame($expectedKeyId, $keyIdParameter->getValue());
    }

    public function provideToken(): iterable
    {
        $hs256 = new HS256();

        yield [SignatureToken::createWithAlgorithm($hs256)];

        $hs512 = new HS512();
        $octetKey = $this->generateOctetKey();

        yield [
            new SignatureToken(
                [
                    Algorithm::fromAlgorithm($hs512),
                    KeyId::fromKey($octetKey),
                ]
            ),
        ];
    }

    public function generateOctetKey(): KeyInterface
    {
        $value = Base64UrlSafe::encode(Rand::getBytes(64));
        $id = Rand::getString(16);

        return new OctetKey($value, $id);
    }
}
