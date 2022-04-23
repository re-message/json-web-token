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

namespace RM\Standard\Jwt\Token;

use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Serializer\SerializerInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
interface TokenInterface
{
    /**
     * Checks if the token is protected by signing, encryption, or something else.
     */
    public function isSecured(): bool;

    /**
     * Returns array collection of header parameters.
     */
    public function getHeader(): Header;

    /**
     * Returns algorithm name used in token.
     */
    public function getAlgorithm(): string;

    /**
     * Returns array collection of payload parameters.
     */
    public function getPayload(): Payload;

    /**
     * Returns serialized token string.
     *
     * @param SerializerInterface<static> $serializer
     */
    public function toString(SerializerInterface $serializer): string;

    /**
     * Creates token instance with algorithm.
     */
    public static function createWithAlgorithm(AlgorithmInterface $algorithm): static;
}
