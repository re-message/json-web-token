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

namespace RM\Standard\Jwt\Serializer;

use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * Interface SerializerInterface provides serialization functional for tokens.
 *
 * @template T of TokenInterface
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
interface SerializerInterface
{
    /**
     * Serializes the token in a transfer-safe and short format.
     *
     * @param T $token
     */
    public function serialize(TokenInterface $token): string;

    /**
     * Deserializes the token from short transfer format.
     *
     * @throws InvalidTokenException
     *
     * @return T
     */
    public function deserialize(string $serialized): TokenInterface;

    /**
     * Checks that serializer supports this token or class for serialization and deserialization.
     *
     * @psalm-assert-if-true T|string $token
     */
    public function supports(TokenInterface|string $token): bool;
}
