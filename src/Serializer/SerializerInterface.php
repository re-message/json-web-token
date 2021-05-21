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

use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * Interface SerializerInterface provides serialization functional for tokens.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
interface SerializerInterface
{
    /**
     * SerializerInterface constructor.
     *
     * @param string $class The token class whose serialization is supported by this serializer.
     */
    public function __construct(string $class);

    /**
     * Serializes the token in a transfer-safe and short format.
     *
     * @param TokenInterface $token
     *
     * @return string
     */
    public function serialize(TokenInterface $token): string;

    /**
     * Deserializes the token from short transfer format.
     *
     * @param string $serialized
     *
     * @return TokenInterface
     * @throws InvalidTokenException
     */
    public function deserialize(string $serialized): TokenInterface;

    /**
     * Checks that serializer supports this token class for serialization and deserialization.
     *
     * @param TokenInterface|string $token The token object or a token FQCN.
     *
     * @return bool
     */
    public function supports($token): bool;
}
