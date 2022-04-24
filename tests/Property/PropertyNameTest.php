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

namespace RM\Standard\Jwt\Tests\Property;

use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Property\Header\Type;
use RM\Standard\Jwt\Property\Payload\Audience;
use RM\Standard\Jwt\Property\Payload\Expiration;
use RM\Standard\Jwt\Property\Payload\Identifier;
use RM\Standard\Jwt\Property\Payload\IssuedAt;
use RM\Standard\Jwt\Property\Payload\Issuer;
use RM\Standard\Jwt\Property\Payload\NotBefore;
use RM\Standard\Jwt\Property\Payload\Subject;
use RM\Standard\Jwt\Property\PropertyInterface;

/**
 * @covers \RM\Standard\Jwt\Property\Header\Type
 * @covers \RM\Standard\Jwt\Property\Payload\Audience
 * @covers \RM\Standard\Jwt\Property\Payload\Expiration
 * @covers \RM\Standard\Jwt\Property\Payload\Identifier
 * @covers \RM\Standard\Jwt\Property\Payload\IssuedAt
 * @covers \RM\Standard\Jwt\Property\Payload\Issuer
 * @covers \RM\Standard\Jwt\Property\Payload\NotBefore
 * @covers \RM\Standard\Jwt\Property\Payload\Subject
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class PropertyNameTest extends TestCase
{
    /**
     * @dataProvider provideClaims
     *
     * @param class-string<PropertyInterface> $propertyClass
     */
    public function testName(string $propertyClass, string $expected): void
    {
        $property = new $propertyClass();
        self::assertSame($property->getName(), $expected);
    }

    public function provideClaims(): iterable
    {
        yield [Type::class, 'typ'];

        yield [Audience::class, 'aud'];

        yield [Audience::class, 'aud'];

        yield [Expiration::class, 'exp'];

        yield [Identifier::class, 'jti'];

        yield [IssuedAt::class, 'iat'];

        yield [Issuer::class, 'iss'];

        yield [NotBefore::class, 'nbf'];

        yield [Subject::class, 'sub'];
    }
}
