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

namespace RM\Standard\Jwt\Tests\Token;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Exception\PropertyNotFoundException;
use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Property\Header\Type;
use RM\Standard\Jwt\Property\PropertyBag;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
#[CoversClass(PropertyBag::class)]
class PropertyBagTest extends TestCase
{
    public function testFind(): void
    {
        $propertyBag = new TestPropertyBag();

        $notExistProperty = $propertyBag->find('not-exist');
        self::assertNull($notExistProperty);

        $expected = new Type('some-type');
        $propertyBag->set($expected);

        $actual = $propertyBag->find(Type::NAME);
        self::assertNotNull($actual);
        self::assertEquals($expected, $actual);
    }

    public function testGet(): void
    {
        $this->expectException(PropertyNotFoundException::class);

        $propertyBag = new TestPropertyBag();
        $propertyBag->get('not-exist');
    }

    public function testHas(): void
    {
        $propertyBag = new TestPropertyBag([new Type('some-type')]);

        self::assertTrue($propertyBag->has(Type::NAME));
        self::assertFalse($propertyBag->has(Algorithm::NAME));
    }

    public function testGetProperties(): void
    {
        $type = new Type('some-type');
        $propertyBag = new TestPropertyBag([$type]);

        self::assertEquals(
            [$type],
            $propertyBag->getProperties(),
        );

        $algorithm = new Algorithm('some-algo');
        $propertyBag->set($algorithm);

        self::assertEquals(
            [$type, $algorithm],
            $propertyBag->getProperties(),
        );
    }

    public function testToArray(): void
    {
        $type = new Type('some-type');
        $propertyBag = new TestPropertyBag([$type]);

        self::assertEquals(
            [Type::NAME => $type->getValue()],
            $propertyBag->toArray(),
        );

        $algorithm = new Algorithm('some-algo');
        $propertyBag->set($algorithm);

        self::assertEquals(
            [
                Type::NAME => $type->getValue(),
                Algorithm::NAME => $algorithm->getValue(),
            ],
            $propertyBag->toArray(),
        );
    }

    public function testCloning(): void
    {
        $type = new Type('some-type');
        $algorithm = new Algorithm('some-algo');
        $properties = [$type, $algorithm];
        $propertyBag = new TestPropertyBag($properties);

        self::assertSame($properties, $propertyBag->getProperties());

        $cloned = clone $propertyBag;
        self::assertNotSame($properties, $cloned->getProperties());
    }
}
