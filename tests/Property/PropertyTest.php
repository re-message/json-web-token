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

namespace RM\Standard\Jwt\Tests\Property;

use Laminas\Math\Rand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Property\AbstractProperty;
use RM\Standard\Jwt\Property\Header\Custom;
use RM\Standard\Jwt\Property\Payload\PrivateClaim;
use RM\Standard\Jwt\Property\Payload\PublicClaim;
use RM\Standard\Jwt\Property\PropertyInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
#[CoversClass(AbstractProperty::class)]
#[CoversClass(Custom::class)]
#[CoversClass(PrivateClaim::class)]
#[CoversClass(PublicClaim::class)]
class PropertyTest extends TestCase
{
    /**
     * @param class-string<PropertyInterface> $claimClass
     */
    #[DataProvider('provideClaimClass')]
    public function testPassValueInConstructor(string $claimClass): void
    {
        $name = Rand::getString(8);
        $value = Rand::getString(16);
        $claim = new $claimClass($name, $value);

        self::assertSame($name, $claim->getName());
        self::assertSame($value, $claim->getValue());
    }

    /**
     * @param class-string<PropertyInterface> $claimClass
     */
    #[DataProvider('provideClaimClass')]
    public function testPassValueInSetter(string $claimClass): void
    {
        $name = Rand::getString(8);
        $value = Rand::getString(16);
        $claim = new $claimClass($name);

        self::assertSame($name, $claim->getName());
        self::assertNull($claim->getValue());

        $claim->setValue($value);
        self::assertSame($value, $claim->getValue());
    }

    public static function provideClaimClass(): iterable
    {
        yield 'custom parameter' => [Custom::class];

        yield 'private claim' => [PrivateClaim::class];

        yield 'public claim' => [PublicClaim::class];
    }
}
