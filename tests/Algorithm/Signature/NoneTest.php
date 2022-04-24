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

namespace RM\Standard\Jwt\Tests\Algorithm\Signature;

use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\None;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @covers \RM\Standard\Jwt\Algorithm\Signature\None
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class NoneTest extends TestCase
{
    public function testName(): void
    {
        $none = new None();
        self::assertSame('none', $none->name());
    }

    public function testAllowedKeys(): void
    {
        $none = new None();
        self::assertContains(KeyInterface::KEY_TYPE_NONE, $none->allowedKeyTypes());
    }

    public function testHash(): void
    {
        $none = new None();
        $key = $this->createMock(KeyInterface::class);

        $input = 'input';
        $signature = $none->hash($key, $input);

        self::assertSame('', $signature);
        self::assertTrue($none->verify($key, $input, $signature));
    }
}