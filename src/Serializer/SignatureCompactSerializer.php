<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Relations Messenger
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
use RM\Standard\Jwt\Factory\ClaimFactory;
use RM\Standard\Jwt\Factory\FactoryInterface;
use RM\Standard\Jwt\Factory\HeaderParameterFactory;
use RM\Standard\Jwt\Serializer\Format\FormatterInterface;
use RM\Standard\Jwt\Serializer\Format\JsonFormatter;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Token\TokenInterface;
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

    private FormatterInterface $formatter;
    private FactoryInterface $claimFactory;
    private FactoryInterface $headerParameterFactory;

    public function __construct(
        ?FormatterInterface $formatter = null,
        ?ClaimFactory $claimFactory = null,
        ?HeaderParameterFactory $headerParameterFactory = null,
    ) {
        $this->formatter = $formatter ?? new JsonFormatter();
        $this->claimFactory = $claimFactory ?? new ClaimFactory();
        $this->headerParameterFactory = $headerParameterFactory ?? new HeaderParameterFactory();
    }

    /**
     * @inheritDoc
     *
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
                    $token::class
                )
            );
        }

        try {
            $jsonHeader = $this->formatter->encode($token->getHeader()->toArray());
            $jsonPayload = $this->formatter->encode($token->getPayload()->toArray());

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
            $headerArray = $this->formatter->decode($jsonHeader);
            $header = $this->createPropertyBag($headerArray, $this->headerParameterFactory);

            $b64Payload = $parts[1];
            $jsonPayload = Base64UrlSafe::decode($b64Payload);
            $payloadArray = $this->formatter->decode($jsonPayload);
            $payload = $this->createPropertyBag($payloadArray, $this->claimFactory);

            if (3 === $count) {
                $b64Signature = $parts[2];
                $signature = Base64UrlSafe::decode($b64Signature);
            }

            return new SignatureToken($header, $payload, $signature ?? null);
        } catch (UnexpectedValueException $e) {
            throw new InvalidTokenException('The token is invalid and cannot be parsed from JSON.', $e);
        }
    }

    private function createPropertyBag(array $properties, FactoryInterface $factory): array
    {
        $bag = [];
        foreach ($properties as $name => $value) {
            $bag[$name] = $factory->create($name, $value);
        }

        return $bag;
    }

    public function supports(TokenInterface|string $token): bool
    {
        return is_a($token, SignatureToken::class, !is_object($token));
    }
}
