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

namespace RM\Standard\Jwt\Tests\Key\Set;

use Laminas\Math\Rand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Exception\UnsupportedKeyException;
use RM\Standard\Jwt\Format\FormatterInterface;
use RM\Standard\Jwt\Format\JsonFormatter;
use RM\Standard\Jwt\Key\Factory\KeyFactoryInterface;
use RM\Standard\Jwt\Key\Factory\OctetKeyFactory;
use RM\Standard\Jwt\Key\Key;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;
use RM\Standard\Jwt\Key\Set\KeySetSerializer;
use RM\Standard\Jwt\Key\Set\KeySetSerializerInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
#[CoversClass(KeySetSerializer::class)]
class KeySetSerializerTest extends TestCase
{
    #[DataProvider('provideKeys')]
    public function testSerialize(array $keys): void
    {
        $factory = $this->createMock(KeyFactoryInterface::class);
        $factory->expects(self::never())->method('create');
        $formatter = new JsonFormatter();

        $serializer = new KeySetSerializer($factory, $formatter);

        $keyToArray = static fn (KeyInterface $key) => $key->all();
        $keysArray = array_map($keyToArray, $keys);
        $expected = $formatter->encode([KeySetSerializerInterface::PARAM_KEYS => $keysArray]);

        $keySet = $serializer->serialize($keys);
        self::assertEquals($expected, $keySet);
    }

    #[DataProvider('provideInvalidKeySet')]
    public function testInvalidKeySet(array $array): void
    {
        $factory = $this->createMock(KeyFactoryInterface::class);
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->method('decode')->willReturn($array);
        $serializer = new KeySetSerializer($factory, $formatter);

        $keySet = $serializer->deserialize('any');
        self::assertEmpty($keySet);
    }

    public static function provideInvalidKeySet(): iterable
    {
        yield 'empty' => [
            [],
        ];

        yield 'no param keys' => [
            [
                'not-keys-param' => [
                    static::generateOctetKey()->all(),
                ],
            ],
        ];

        yield 'keys not as an array' => [
            [
                KeySetSerializerInterface::PARAM_KEYS => [
                    'key',
                    'another key',
                ],
            ],
        ];
    }

    #[DataProvider('provideKeys')]
    public function testDeserialize(array $expected): void
    {
        $factory = new OctetKeyFactory();

        $keyToArray = static fn (KeyInterface $key) => $key->all();
        $keysArray = array_map($keyToArray, $expected);
        $keySet = [KeySetSerializerInterface::PARAM_KEYS => $keysArray];

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->method('decode')->willReturn($keySet);

        $serializer = new KeySetSerializer($factory, $formatter);

        $keys = $serializer->deserialize('any');
        self::assertEquals($expected, $keys);
    }

    public static function provideKeys(): iterable
    {
        yield 'empty' => [[]];

        yield 'one octet key' => [[static::generateOctetKey()]];
    }

    public function testSkipOnInvalidKey(): void
    {
        $firstKey = static::generateOctetKey();
        $secondKey = static::generateOctetKey();
        $keySet = [
            KeySetSerializerInterface::PARAM_KEYS => [
                $firstKey->all(),
                $secondKey->all(),
            ],
        ];

        $factory = $this->createMock(KeyFactoryInterface::class);
        $factory
            ->method('create')
            ->willReturnCallback(
                fn (array $key) => $key !== $firstKey->all()
                    ? $secondKey
                    : throw new UnsupportedKeyException('this', KeyFactoryInterface::class),
            )
        ;

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->method('decode')->willReturn($keySet);

        $serializer = new KeySetSerializer($factory, $formatter);

        $keys = $serializer->deserialize('any');
        self::assertCount(1, $keys);
        self::assertEquals([$secondKey], $keys);
    }

    private static function generateOctetKey(): KeyInterface
    {
        $value = Rand::getString(16);
        $id = Rand::getString(12);

        return new Key(
            [
                new Type(Type::OCTET),
                new Value($value),
                new Identifier($id),
            ]
        );
    }
}
