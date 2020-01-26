<?php
/**
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://gitlab.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Security\Jwt\Token;

use RM\Security\Jwt\Serializer\SerializerInterface;

/**
 * Interface TokenInterface
 *
 * @package RM\Security\Jwt\Token
 * @author  h1karo <h1karo@outlook.com>
 */
interface TokenInterface
{
    /**
     * Returns array collection of header parameters.
     *
     * @return Header
     */
    public function getHeader(): Header;

    /**
     * Returns array collection of payload parameters.
     *
     * @return Payload
     */
    public function getPayload(): Payload;

    /**
     * Returns serialized token string.
     *
     * @param SerializerInterface $serializer
     *
     * @return string
     */
    public function toString(SerializerInterface $serializer): string;
}