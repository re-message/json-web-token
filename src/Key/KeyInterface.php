<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Key;

use JsonSerializable;

/**
 * Interface KeyInterface implements JSON Web Key standard (RFC 7517)
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 * @see    https://tools.ietf.org/pdf/rfc7517
 */
interface KeyInterface extends JsonSerializable
{
    public const PARAM_KEY_TYPE  = 'kty';
    public const PARAM_KEY_VALUE = 'k';

    public const KEY_TYPE_OCTET = 'oct';
    public const KEY_TYPE_RSA   = 'RSA';

    /**
     * Returns value of parameter if he exists.
     *
     * @param string $parameter
     *
     * @return string
     */
    public function get(string $parameter): string;

    /**
     * Checks if a parameter exists in a key.
     *
     * @param string $parameter
     *
     * @return bool
     */
    public function has(string $parameter): bool;

    /**
     * Returns the type of the key.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Returns all parameters for this key or array key format
     *
     * @return array
     */
    public function all(): array;
}
