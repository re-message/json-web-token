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

namespace RM\Standard\Jwt\Tests\Format;

use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Format\JsonFormatter;

/**
 * @covers \RM\Standard\Jwt\Format\JsonFormatter
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class JsonFormatterTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testEncode(array $data, string $expected): void
    {
        $formatter = new JsonFormatter();
        $actual = $formatter->encode($data);
        self::assertSame($expected, $actual);
    }

    /**
     * @dataProvider provideData
     */
    public function testDecode(array $expected, string $data): void
    {
        $formatter = new JsonFormatter();
        $actual = $formatter->decode($data);
        self::assertSame($expected, $actual);
    }

    public function provideData(): iterable
    {
        yield [['bool' => true], '{"bool":true}'];

        yield [['number' => 25], '{"number":25}'];

        yield [['string' => 'string'], '{"string":"string"}'];

        yield [
            [
                'bool' => true,
                'number' => 25,
                'string' => 'string',
            ],
            '{"bool":true,"number":25,"string":"string"}',
        ];
    }
}
