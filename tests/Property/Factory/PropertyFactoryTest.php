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

namespace RM\Standard\Jwt\Tests\Property\Factory;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Property\Factory\AbstractPropertyFactory;
use RM\Standard\Jwt\Property\Factory\ClaimFactory;
use RM\Standard\Jwt\Property\Factory\HeaderParameterFactory;
use RM\Standard\Jwt\Property\Header\Custom;
use RM\Standard\Jwt\Property\Payload\PrivateClaim;
use RM\Standard\Jwt\Tests\Property\SomeProperty;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
#[CoversClass(AbstractPropertyFactory::class)]
#[CoversClass(ClaimFactory::class)]
#[CoversClass(HeaderParameterFactory::class)]
class PropertyFactoryTest extends TestCase
{
    /**
     * @param class-string $factoryClass
     * @param class-string $expectedClass
     */
    #[DataProvider('provideFactory')]
    public function testCreate(string $factoryClass, string $expectedClass): void
    {
        $factory = new $factoryClass();

        $property = $factory->create(SomeProperty::NAME, 'some-value');
        self::assertInstanceOf($expectedClass, $property);

        $factory->register(SomeProperty::NAME, SomeProperty::class);
        $property = $factory->create(SomeProperty::NAME, 'some-value');
        self::assertInstanceOf(SomeProperty::class, $property);
    }

    public static function provideFactory(): iterable
    {
        yield 'header parameter factory & some' => [
            HeaderParameterFactory::class,
            Custom::class,
        ];

        yield 'claim factory & some' => [
            ClaimFactory::class,
            PrivateClaim::class,
        ];
    }
}
