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

namespace RM\Standard\Jwt\Serializer;

use InvalidArgumentException;
use ParagonIE\ConstantTime\Base64UrlSafe;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Token\SignatureToken;
use RM\Standard\Jwt\Token\TokenInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

/**
 * Class CompactSerializer provides JWS Compact Serialization.
 * Compact serialization is a serialization in URL-safe format.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class SignatureCompactSerializer implements SignatureSerializerInterface
{
    /**
     * Delimiter between header, payload and signature parts for compact serialized token.
     *
     * @see SignatureCompactSerializer::serialize()
     * @see SignatureCompactSerializer::deserialize()
     */
    public const TOKEN_DELIMITER = '.';

    /**
     * The token class whose serialization is supported by this serializer.
     *
     * @var string
     */
    private string $class;
    private JsonEncoder $encoder;

    /**
     * @inheritDoc
     */
    public function __construct(string $class = SignatureToken::class)
    {
        $this->class = $class;
        $this->encoder = new JsonEncoder();
    }

    /**
     * @inheritDoc
     * @throws InvalidTokenException
     */
    public function serialize(TokenInterface $token, bool $withoutSignature = false): string
    {
        if (!$token instanceof SignatureToken) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s can serialize only %s, given %s',
                    self::class,
                    SignatureToken::class,
                    get_class($token)
                )
            );
        }

        try {
            $jsonHeader = $this->encode($token->getHeader()->toArray());
            $jsonPayload = $this->encode($token->getPayload()->toArray());

            $b64Header = Base64UrlSafe::encodeUnpadded($jsonHeader);
            $b64Payload = Base64UrlSafe::encodeUnpadded($jsonPayload);

            if (!$withoutSignature && !empty($token->getSignature())) {
                $b64Signature = Base64UrlSafe::encodeUnpadded($token->getSignature());
                $parts = [$b64Header, $b64Payload, $b64Signature];
            } else {
                $parts = [$b64Header, $b64Payload];
            }

            return implode(self::TOKEN_DELIMITER, $parts);
        } catch (UnexpectedValueException $e) {
            throw new InvalidTokenException('The token data is invalid and cannot be serialized in JSON.', $e);
        }
    }

    protected function encode(array $data): string
    {
        return $this->encoder->encode($data, 'json');
    }

    /**
     * @inheritDoc
     */
    public function deserialize(string $serialized): TokenInterface
    {
        $parts = explode(self::TOKEN_DELIMITER, $serialized);
        $count = count($parts);
        if ($count < 2 || $count > 3) {
            throw new InvalidTokenException('Token must implement JSON Web Token standard or any related standard.');
        }

        try {
            $b64Header = $parts[0];
            $jsonHeader = Base64UrlSafe::decode($b64Header);
            $header = $this->decode($jsonHeader);

            $b64Payload = $parts[1];
            $jsonPayload = Base64UrlSafe::decode($b64Payload);
            $payload = $this->decode($jsonPayload);

            if ($count === 3) {
                $b64Signature = $parts[2];
                $signature = Base64UrlSafe::decode($b64Signature);
            }

            return new $this->class($header, $payload, $signature ?? null);
        } catch (UnexpectedValueException $e) {
            throw new InvalidTokenException('The token is invalid and cannot be parsed from JSON.', $e);
        }
    }

    protected function decode(string $data): array
    {
        return $this->encoder->decode($data, 'json');
    }

    /**
     * @inheritDoc
     */
    public function supports($token): bool
    {
        return is_a($token, $this->class, !is_object($token));
    }
}
