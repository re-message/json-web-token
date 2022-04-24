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

namespace RM\Standard\Jwt\Tests\Serializer;

use Generator;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3512;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Serializer\SignatureCompactSerializer;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Token\TokenInterface;
use stdClass;

/**
 * @covers \RM\Standard\Jwt\Serializer\SignatureCompactSerializer
 *
 * @internal
 */
class SignatureCompactSerializerTest extends TestCase
{
    public function testSupports(): SignatureCompactSerializer
    {
        $serializer = new SignatureCompactSerializer();

        self::assertTrue($serializer->supports(SignatureToken::class));
        self::assertFalse($serializer->supports(stdClass::class));

        $token = SignatureToken::createWithAlgorithm(new HS3512());
        self::assertTrue($serializer->supports($token));

        return $serializer;
    }

    /**
     * @dataProvider getTokens
     */
    public function testSerialize(bool $isValid, string $rawToken): TokenInterface
    {
        $serializer = new SignatureCompactSerializer();

        if (!$isValid) {
            $this->expectException(InvalidTokenException::class);
        }

        $token = $serializer->deserialize($rawToken);
        self::assertInstanceOf(SignatureToken::class, $token);

        $serializedToken = $token->toString($serializer);
        self::assertEquals($rawToken, $serializedToken);

        return $token;
    }

    public function getTokens(): Generator
    {
        yield [
            true,
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c',
        ];

        yield [
            false,
            'eyJhbGciOiJIUzI1NiJ9.SGVsbG8sIHdvcmxkIQ.onO9Ihudz3WkiauDO2Uhyuz0Y18UASXlSc1eS0NkWyA',
        ];

        yield [
            false,
            'this.is.invalid.token',
        ];
    }
}
