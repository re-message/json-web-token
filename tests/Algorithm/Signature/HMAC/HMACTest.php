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

namespace RM\Standard\Jwt\Tests\Algorithm\Signature\HMAC;

use InvalidArgumentException;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HMAC;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3512;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512;
use RM\Standard\Jwt\Key\Key;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;

/**
 * @internal
 */
#[CoversClass(HMAC::class)]
#[CoversClass(HS256::class)]
#[CoversClass(HS3256::class)]
#[CoversClass(HS3512::class)]
#[CoversClass(HS512::class)]
class HMACTest extends TestCase
{
    private KeyInterface $key;

    #[Override]
    protected function setUp(): void
    {
        $b64Key = '-2iJCM1Dgovi-djDb9Xb-_EQxxxRTVl_y6S6r7mlpPM';

        $this->key = new Key(
            [
                new Type(Type::OCTET),
                new Value($b64Key),
            ],
        );
    }

    #[DataProvider('provideAlgorithms')]
    public function testAlgorithmName(HMAC $algorithm): void
    {
        $reflect = new ReflectionClass($algorithm);
        $expected = strtoupper($reflect->getShortName());
        self::assertSame($expected, $algorithm->name());
    }

    #[DataProvider('provideAlgorithms')]
    public function testOctetKeyIsAllowed(HMAC $algorithm): void
    {
        self::assertContains($this->key->getType(), $algorithm->allowedKeyTypes());
    }

    public static function provideAlgorithms(): iterable
    {
        $algorithms = [new HS256(), new HS512(), new HS3256(), new HS3512()];
        foreach ($algorithms as $algorithm) {
            yield $algorithm->name() => [$algorithm];
        }
    }

    #[DataProvider('provideHashes')]
    public function testHash(HMAC $algorithm, string $input, string $expected): void
    {
        $hash = $algorithm->sign($this->key, $input);
        self::assertTrue(hash_equals($expected, $hash));

        self::assertTrue($algorithm->verify($this->key, $input, $hash));
        self::assertFalse($algorithm->verify($this->key, 'bad-input', $hash));
        self::assertFalse($algorithm->verify($this->key, $input, 'bad-hash'));
        self::assertFalse($algorithm->verify($this->key, 'bad-input', 'bad-hash'));
    }

    public static function provideHashes(): iterable
    {
        yield [
            new HS256(),
            'input',
            hex2bin('2062ae27ddaa4f3979800505af608d0a0160d92819d5b19cae9b61654a0909fd'),
        ];

        yield [
            new HS512(),
            'input',
            hex2bin(
                '58c5dadbf34c1bc6c072066b9cf13a90dcc127ffed89f56fcf99487e823d836779d037b762f635b6eb86471bd6728f0ff902e39a93da973099502770f6c8c846'
            ),
        ];

        yield [
            new HS3256(),
            'input',
            hex2bin('6106021e36ac5850a5487d8202408e938adcbb6f1d8ff2309967e2697e95821a'),
        ];

        yield [
            new HS3512(),
            'input',
            hex2bin(
                'df601769b1a6de723df10a4554d78f90767d849a8e090ab262785d51de000bcf156ab7af5d0143fb467e4c275942fb8cef3d1dbb55b9c7351e052074e07895c2'
            ),
        ];
    }

    #[DataProvider('provideInvalidKeys')]
    public function testInvalidKey(KeyInterface $key, string $message): void
    {
        $algorithm = new HS256();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);
        $algorithm->sign($key, 'any');
    }

    public static function provideInvalidKeys(): iterable
    {
        yield 'invalid type key' => [
            new Key([new Type('unknown key')]),
            'key type',
        ];

        yield 'no value key' => [
            new Key([new Type(Type::OCTET)]),
            '"k" is missing',
        ];

        yield 'short value key' => [
            new Key(
                [
                    new Type(Type::OCTET),
                    new Value('short-value'),
                ]
            ),
            'key length',
        ];
    }
}
