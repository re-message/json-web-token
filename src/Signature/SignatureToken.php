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

namespace RM\Standard\Jwt\Signature;

use InvalidArgumentException;
use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Serializer\SerializerInterface;
use RM\Standard\Jwt\Serializer\SignatureCompactSerializer;
use RM\Standard\Jwt\Token\Header;
use RM\Standard\Jwt\Token\Payload;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * Class SignatureToken implements JSON Web Signature standard (RFC 7515).
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see https://tools.ietf.org/pdf/rfc7515
 */
final class SignatureToken implements TokenInterface
{
    private Header $header;
    private Payload $payload;

    /**
     * Token signature.
     * Empty signature is a valid signature with {@see None}.
     *
     * @see SignerInterface::sign()
     */
    private ?string $signature;

    public function __construct(array $header, array $payload = [], string $signature = null)
    {
        $this->header = new Header($header);
        $this->payload = new Payload($payload);
        $this->signature = $signature;
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function getAlgorithm(): string
    {
        return $this->header->get(Algorithm::NAME)->getValue();
    }

    /**
     * Returns new instance of the token with updated algorithm.
     */
    public function setAlgorithm(SignatureAlgorithmInterface $algorithm): static
    {
        $token = clone $this;

        $property = Algorithm::fromAlgorithm($algorithm);
        $token->header->set($property);

        return $token;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    /**
     * Returns current token signature.
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * Returns new instance of the token with signature.
     */
    public function setSignature(?string $signature): self
    {
        $token = clone $this;
        $token->signature = $signature;

        return $token;
    }

    /**
     * Defines that signature successful signed or not.
     *
     * @psalm-assert-if-true string $this->signature
     * @psalm-assert-if-true string $this->getSignature()
     */
    public function isSigned(): bool
    {
        return null !== $this->signature;
    }

    public function toString(SerializerInterface $serializer): string
    {
        if (!$serializer->supports($this)) {
            $message = sprintf('%s can not be serialized with %s.', self::class, $serializer::class);

            throw new InvalidArgumentException($message);
        }

        return $serializer->serialize($this);
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
     * @see SignatureCompactSerializer::serialize()
     */
    public function __toString()
    {
        $serializer = new SignatureCompactSerializer();

        return $this->toString($serializer);
    }

    public static function createWithAlgorithm(AlgorithmInterface $algorithm): static
    {
        $algorithmParameter = Algorithm::fromAlgorithm($algorithm);
        $header = [$algorithmParameter];

        return new self($header);
    }
}
