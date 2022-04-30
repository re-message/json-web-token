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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Signature\SignatureToken;

/**
 * @covers \RM\Standard\Jwt\Signature\SignatureToken
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class SignatureTokenTest extends TestCase
{
    public function testAlgorithmRequired(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must have the algorithm parameter');

        new SignatureToken([], []);
    }

    public function testNoSignature(): void
    {
        $token = new SignatureToken([new Algorithm('algo')], [], null);

        self::assertNull($token->getSignature());
        self::assertFalse($token->isSigned());
        self::assertFalse($token->isSecured());
    }

    public function testEmptySignature(): void
    {
        $token = new SignatureToken([new Algorithm('algo')], [], '');

        self::assertNotNull($token->getSignature());
        self::assertEmpty($token->getSignature());
        self::assertTrue($token->isSigned());
        self::assertFalse($token->isSecured());
    }

    public function testCloningOnChangeSignature(): void
    {
        $token = new SignatureToken([new Algorithm('algo')], [], null);
        self::assertNull($token->getSignature());

        $newToken = $token->setSignature('signature');
        self::assertNotNull($newToken->getSignature());
        self::assertNull($token->getSignature());
        self::assertNotSame($newToken, $token);
    }
}
