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

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Signature\LoggableSigner;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\SignerInterface;

/**
 * @covers \RM\Standard\Jwt\Signature\LoggableSigner
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class LoggableSignerTest extends TestCase
{
    public function testLogging(): void
    {
        $innerSigner = $this->createMock(SignerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $signer = new LoggableSigner($innerSigner, $logger);

        $algorithm = new HS256();
        $token = SignatureToken::createWithAlgorithm($algorithm);
        $key = $this->createMock(KeyInterface::class);

        $innerSigner
            ->expects(self::once())
            ->method('sign')
            ->willReturnArgument(0)
        ;

        $logger
            ->expects(self::once())
            ->method('debug')
            ->with(
                self::anything(),
                self::logicalAnd(
                    self::isType('array'),
                    self::arrayHasKey('algorithm'),
                    self::containsEqual($algorithm->name()),
                ),
            )
        ;

        $signer->sign($token, $algorithm, $key);
    }
}
