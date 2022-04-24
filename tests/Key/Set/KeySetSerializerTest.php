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

namespace RM\Standard\Jwt\Tests\Key\Set;

use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Format\FormatterInterface;
use RM\Standard\Jwt\Key\Factory\KeyFactoryInterface;
use RM\Standard\Jwt\Key\Factory\OctetKeyFactory;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\OctetKey;
use RM\Standard\Jwt\Key\Set\KeySetSerializer;
use RM\Standard\Jwt\Key\Set\KeySetSerializerInterface;

/**
 * @covers \RM\Standard\Jwt\Key\Set\KeySetSerializer
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class KeySetSerializerTest extends TestCase
{
    /**
     * @dataProvider provideInvalidKeySet
     */
    public function testInvalidKeySet(array $array): void
    {
        $factory = $this->createMock(KeyFactoryInterface::class);
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->method('decode')->willReturn($array);
        $serializer = new KeySetSerializer($factory, $formatter);

        $keySet = $serializer->deserialize('any');
        self::assertEmpty($keySet);
    }

    public function provideInvalidKeySet(): iterable
    {
        yield 'empty' => [
            [],
        ];

        yield 'no param keys' => [
            [
                'not-keys-param' => [
                    $this->getOctetKey()->all(),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideKeys
     */
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

    public function provideKeys(): iterable
    {
        yield 'empty' => [[]];

        yield 'one octet key' => [[$this->getOctetKey()]];
    }

    private function getOctetKey(): OctetKey
    {
        return new OctetKey('GawgguFyGrWKav7AX4VKUg', 'fexa');
    }
}
