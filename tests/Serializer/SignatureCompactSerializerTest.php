<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2021 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
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
use RM\Standard\Jwt\Token\SignatureToken;
use RM\Standard\Jwt\Token\TokenInterface;
use stdClass;

class SignatureCompactSerializerTest extends TestCase
{
    public function testSupports(): SignatureCompactSerializer
    {
        $serializer = new SignatureCompactSerializer();

        self::assertTrue($serializer->supports(SignatureToken::class));
        self::assertFalse($serializer->supports(stdClass::class));

        $token = SignatureToken::createWithAlgorithm(new HS3512());
        self::assertTrue($serializer->supports($token));
        self::assertFalse($serializer->supports(new stdClass()));

        return $serializer;
    }

    /**
     * @depends      testSupports
     * @dataProvider getTokens
     */
    public function testSerialize(bool $isValid, string $rawToken, SignatureCompactSerializer $serializer): TokenInterface
    {
        if (!$isValid) {
            $this->expectException(InvalidTokenException::class);
        }

        $token = $serializer->deserialize($rawToken);
        self::assertInstanceOf(SignatureToken::class, $token);

        $serializedToken = $token->toString($serializer);
        self::assertNotNull($serializedToken);
        self::assertEquals($rawToken, $serializedToken);

        return $token;
    }

    public function getTokens(): Generator
    {
        yield [
            true,
            'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c'
        ];
        yield [false, 'eyJhbGciOiJIUzI1NiJ9.SGVsbG8sIHdvcmxkIQ.onO9Ihudz3WkiauDO2Uhyuz0Y18UASXlSc1eS0NkWyA'];
        yield [false, 'this.is.invalid.token'];
    }
}
