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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512;
use RM\Standard\Jwt\Event\AbstractSignEvent;
use RM\Standard\Jwt\Event\TokenPreSignEvent;
use RM\Standard\Jwt\Event\TokenSignEvent;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Signature\EventfulSigner;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\SignerInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
#[CoversClass(EventfulSigner::class)]
class EventfulSignerTest extends TestCase
{
    public function testLogging(): void
    {
        $innerSigner = $this->createMock(SignerInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $signer = new EventfulSigner($innerSigner, $eventDispatcher);

        $algorithm = new HS256();
        $token = SignatureToken::createWithAlgorithm($algorithm);
        $key = $this->createMock(KeyInterface::class);

        $innerSigner
            ->expects(self::once())
            ->method('sign')
            ->willReturnArgument(0)
        ;

        $eventTypeCheck = fn (object $event) => $event instanceof TokenPreSignEvent || $event instanceof TokenSignEvent;
        $eventDispatcher
            ->expects(self::exactly(2))
            ->method('dispatch')
            ->with(self::callback($eventTypeCheck))
        ;

        $signer->sign($token, $algorithm, $key);
    }

    public function testReturnNewTokenFromMethod(): void
    {
        $innerSigner = $this->createMock(SignerInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $signer = new EventfulSigner($innerSigner, $eventDispatcher);

        $algorithm = new HS256();
        $token = SignatureToken::createWithAlgorithm($algorithm);
        $key = $this->createMock(KeyInterface::class);

        $innerSigner
            ->expects(self::once())
            ->method('sign')
            ->willReturnArgument(0)
        ;

        $eventTypeCheck = fn (object $event) => $event instanceof TokenPreSignEvent || $event instanceof TokenSignEvent;
        $eventDispatcher
            ->expects(self::exactly(2))
            ->method('dispatch')
            ->with(self::callback($eventTypeCheck))
            ->willReturnCallback(static function (AbstractSignEvent $event): void {
                if (!$event instanceof TokenSignEvent) {
                    return;
                }

                $token = SignatureToken::createWithAlgorithm(new HS512());
                $event->setToken($token);
            })
        ;

        $newToken = $signer->sign($token, $algorithm, $key);
        self::assertNotSame($token, $newToken);
    }
}
