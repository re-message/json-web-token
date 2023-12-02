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

namespace RM\Standard\Jwt\Tests\Format;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Format\JsonFormatter;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
#[CoversClass(JsonFormatter::class)]
class JsonFormatterTest extends TestCase
{
    #[DataProvider('provideData')]
    public function testEncode(array $data, string $expected): void
    {
        $formatter = new JsonFormatter();
        $actual = $formatter->encode($data);
        self::assertSame($expected, $actual);
    }

    #[DataProvider('provideData')]
    public function testDecode(array $expected, string $data): void
    {
        $formatter = new JsonFormatter();
        $actual = $formatter->decode($data);
        self::assertSame($expected, $actual);
    }

    public function provideData(): iterable
    {
        yield 'empty' => [[], '{}'];

        yield 'list' => [[1, 2, 3], '[1,2,3]'];

        yield 'bool' => [['bool' => true], '{"bool":true}'];

        yield 'number' => [['number' => 25], '{"number":25}'];

        yield 'string' => [['string' => 'string'], '{"string":"string"}'];

        yield 'all' => [
            [
                'bool' => true,
                'number' => 25,
                'string' => 'string',
            ],
            '{"bool":true,"number":25,"string":"string"}',
        ];
    }
}
