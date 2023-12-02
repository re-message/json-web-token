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

namespace RM\Standard\Jwt\Tests\Property\Payload;

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Property\Payload\DateValueClaim;
use RM\Standard\Jwt\Property\Payload\Expiration;
use RM\Standard\Jwt\Property\Payload\IssuedAt;
use RM\Standard\Jwt\Property\Payload\NotBefore;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
#[CoversClass(DateValueClaim::class)]
class DateValueClaimTest extends TestCase
{
    /**
     * @param class-string<DateValueClaim> $claimClass
     */
    #[DataProvider('provideClaimClass')]
    public function testSetValue(string $claimClass): void
    {
        $claim = new $claimClass(new DateTime());

        $date = $this->getRandomDate();
        $timestamp = $date->getTimestamp();

        $claim->setValue($timestamp);
        self::assertSame($timestamp, $claim->getValue());
    }

    /**
     * @param class-string<DateValueClaim> $claimClass
     */
    #[DataProvider('provideClaimClass')]
    public function testDateTimeUsage(string $claimClass): void
    {
        $claim = new $claimClass(new DateTime());

        $date = $this->getRandomDate();
        $claim->setValue($date);
        self::assertSame($date->getTimestamp(), $claim->getValue());

        $immutableDate = DateTimeImmutable::createFromMutable($this->getRandomDate());
        $claim->setValue($immutableDate);
        self::assertSame($immutableDate->getTimestamp(), $claim->getValue());
    }

    protected function getRandomDate(): DateTime
    {
        $startDate = DateTime::createFromFormat('Y-m-d H:i:s', '2009-02-15 15:16:17');
        $endDate = new DateTime();
        $timestamp = random_int($startDate->getTimestamp(), $endDate->getTimestamp());

        return DateTime::createFromFormat('U', $timestamp);
    }

    public static function provideClaimClass(): iterable
    {
        yield 'expiration' => [Expiration::class];

        yield 'issued at' => [IssuedAt::class];

        yield 'not before' => [NotBefore::class];
    }
}
