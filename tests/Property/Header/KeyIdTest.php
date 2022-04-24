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

namespace RM\Standard\Jwt\Tests\Property\Header;

use InvalidArgumentException;
use Laminas\Math\Rand;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Property\Header\KeyId;

/**
 * @covers \RM\Standard\Jwt\Property\Header\KeyId
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class KeyIdTest extends TestCase
{
    public function testName(): void
    {
        $keyId = new KeyId();
        self::assertSame('kid', $keyId->getName());
    }

    public function testSecondaryConstructor(): void
    {
        $id = Rand::getString(32);

        $key = $this->createMock(KeyInterface::class);
        $key
            ->expects(self::once())
            ->method('has')
            ->with(KeyInterface::PARAM_KEY_IDENTIFIER)
            ->willReturn(true)
        ;
        $key
            ->expects(self::once())
            ->method('get')
            ->with(KeyInterface::PARAM_KEY_IDENTIFIER)
            ->willReturn($id)
        ;

        $parameter = KeyId::fromKey($key);
        self::assertSame($id, $parameter->getValue());
    }

    public function testNoIdInSecondaryConstructor(): void
    {
        $key = $this->createMock(KeyInterface::class);
        $key
            ->expects(self::once())
            ->method('has')
            ->with(KeyInterface::PARAM_KEY_IDENTIFIER)
            ->willReturn(false)
        ;
        $key
            ->expects(self::never())
            ->method('get')
        ;

        $this->expectException(InvalidArgumentException::class);
        KeyId::fromKey($key);
    }
}
