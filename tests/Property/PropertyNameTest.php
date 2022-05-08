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

use DateTime;
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
    public function testName(string $propertyClass, mixed $value, string $expected): void
    {
        $property = new $propertyClass($value);
        self::assertSame($property->getName(), $expected);
    }

    public function provideClaims(): iterable
    {
        yield [Type::class, 'some-type', 'typ'];

        yield [Audience::class, ['some-audience'], 'aud'];

        yield [Expiration::class, new DateTime(), 'exp'];

        yield [Identifier::class, 'some-id', 'jti'];

        yield [IssuedAt::class, new DateTime(), 'iat'];

        yield [Issuer::class, 'asrfw', 'iss'];

        yield [NotBefore::class, new DateTime(), 'nbf'];

        yield [Subject::class, 'some-subject', 'sub'];
    }
}
