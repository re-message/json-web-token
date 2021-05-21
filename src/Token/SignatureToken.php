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

namespace RM\Standard\Jwt\Token;

use InvalidArgumentException;
use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\HeaderParameter\Algorithm;
use RM\Standard\Jwt\Serializer\SerializerInterface;
use RM\Standard\Jwt\Serializer\SignatureCompactSerializer;
use RM\Standard\Jwt\Serializer\SignatureSerializerInterface;
use RM\Standard\Jwt\Service\SignatureServiceInterface;

/**
 * Class SignatureToken implements JSON Web Signature standard (RFC 7515)
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 * @see    https://tools.ietf.org/pdf/rfc7515
 * @final since 2.0
 */
class SignatureToken implements TokenInterface
{
    private Header $header;
    private Payload $payload;

    /**
     * Token signature.
     * Empty signature is a valid signature with {@see None}.
     *
     * @var string|null
     * @see SignatureServiceInterface::sign()
     */
    private ?string $signature;

    final public function __construct(array $header, array $payload = [], string $signature = null)
    {
        $this->header = new Header($header);
        $this->payload = new Payload($payload);
        $this->signature = $signature;
    }

    /**
     * @inheritDoc
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * @inheritDoc
     */
    public function getAlgorithm(): string
    {
        return $this->header->get(Algorithm::NAME)->getValue();
    }

    /**
     * Returns new instance of the token with updated algorithm.
     *
     * @param SignatureAlgorithmInterface $algorithm
     *
     * @return TokenInterface
     */
    public function setAlgorithm(SignatureAlgorithmInterface $algorithm): TokenInterface
    {
        $token = clone $this;
        $token->header->set(Algorithm::fromAlgorithm($algorithm));
        return $token;
    }

    /**
     * @inheritDoc
     */
    public function getPayload(): Payload
    {
        return $this->payload;
    }

    /**
     * Returns current token signature.
     *
     * @return string|null
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * Returns new instance of the token with signature.
     *
     * @param string|null $signature
     *
     * @return SignatureToken
     */
    public function setSignature(?string $signature): SignatureToken
    {
        $token = clone $this;
        $token->signature = $signature;
        return $token;
    }

    /**
     * Defines that signature successful signed or not.
     *
     * @return bool
     */
    public function isSigned(): bool
    {
        return $this->signature !== null;
    }

    /**
     * @inheritDoc
     */
    public function toString(SerializerInterface $serializer, bool $withoutSignature = false): string
    {
        if (!$serializer instanceof SignatureSerializerInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s can be serialized only with %s.',
                    self::class,
                    SignatureSerializerInterface::class
                )
            );
        }

        return $serializer->serialize($this, $withoutSignature);
    }

    /**
     * On cloning the signature should be removed.
     */
    public function __clone()
    {
        $this->header = clone $this->header;
        $this->payload = clone $this->payload;
        $this->signature = null;
    }

    /**
     * Returns compact serialized token.
     *
     * @return string
     * @see SignatureCompactSerializer::serialize()
     */
    public function __toString()
    {
        $serializer = new SignatureCompactSerializer(self::class);
        return $this->toString($serializer);
    }

    /**
     * @inheritDoc
     */
    final public static function createWithAlgorithm(AlgorithmInterface $algorithm): self
    {
        $algorithmClaim = Algorithm::fromAlgorithm($algorithm);

        return new static([$algorithmClaim]);
    }
}
