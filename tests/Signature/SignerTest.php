<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
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
use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\OctetKey;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\Signer;

/**
 * @internal
 */
class SignerTest extends TestCase
{
    /**
     * @dataProvider provideKeyAndAlgorithm
     */
    public function testSign(SignatureAlgorithmInterface $algorithm, KeyInterface $key): void
    {
        $token = SignatureToken::createWithAlgorithm($algorithm);

        $signer = new Signer();
        $signed = $signer->sign($token, $algorithm, $key);

        self::assertNotSame($signed, $token);
        self::assertFalse($token->isSigned());
        self::assertTrue($signed->isSigned());
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

    public function provideKeyAndAlgorithm(): Generator
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

    public function generateOctetKey(): KeyInterface
    {
        return new OctetKey(Base64UrlSafe::encode(Rand::getBytes(64)));
    }
}
