<?php
/*
 * This file is a part of Re Message Json Web Token implementation.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/json-web-token
 * @link      https://dev.remessage.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2023 Re Message
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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512;
use RM\Standard\Jwt\Algorithm\Signature\None;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Key\Key;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;
use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Property\Header\KeyId;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\Signer;

/**
 * @internal
 */
#[CoversClass(Signer::class)]
class SignerTest extends TestCase
{
    #[DataProvider('provideKeyAndAlgorithm')]
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

    #[DataProvider('provideKeyAndAlgorithm')]
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

    public static function provideKeyAndAlgorithm(): iterable
    {
        $cartesian = new CartesianProduct(
            [
                iterator_to_array(static::getAlgorithms()),
                iterator_to_array(static::getKeys()),
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

    public static function getAlgorithms(): Generator
    {
        yield new HS256();

        yield new HS3256();
    }

    public static function getKeys(): Generator
    {
        yield static::generateOctetKey();
    }

    #[DataProvider('provideToken')]
    public function testSignUpdatesAlgorithmAndKey(SignatureToken $token): void
    {
        $algorithm = new HS3256();
        $key = $this->generateOctetKey();

        $signer = new Signer();
        $signed = $signer->sign($token, $algorithm, $key);

        $algorithmParameter = $signed->getHeader()->get(Algorithm::NAME);
        self::assertSame($algorithm->name(), $algorithmParameter->getValue());

        $tokenKeyId = $signed->getHeader()->get(KeyId::NAME)->getValue();
        $keyId = $key->get(Identifier::NAME)->getValue();
        self::assertSame($keyId, $tokenKeyId);
    }

    public static function provideToken(): iterable
    {
        $hs256 = new HS256();

        yield [SignatureToken::createWithAlgorithm($hs256)];

        $hs512 = new HS512();
        $octetKey = static::generateOctetKey();

        yield [
            new SignatureToken(
                [
                    Algorithm::fromAlgorithm($hs512),
                    KeyId::fromKey($octetKey),
                ]
            ),
        ];
    }

    public static function generateOctetKey(): KeyInterface
    {
        $value = Base64UrlSafe::encode(Rand::getBytes(64));
        $id = Rand::getString(16);

        return new Key(
            [
                new Type(Type::OCTET),
                new Value($value),
                new Identifier($id),
            ]
        );
    }
}
