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

namespace RM\Standard\Jwt\Key;

use JsonSerializable;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Key\Parameter\KeyUse;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;

/**
 * Interface KeyInterface implements JSON Web Key standard (RFC 7517).
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see https://datatracker.ietf.org/doc/html/rfc7517
 */
interface KeyInterface extends JsonSerializable
{
    public const PARAM_TYPE = Type::NAME;
    public const PARAM_IDENTIFIER = Identifier::NAME;
    public const PARAM_USE = KeyUse::NAME;
    public const PARAM_VALUE = Value::NAME;
    public const PARAM_OPERATIONS = 'key_ops';

    public const TYPE_NONE = 'none';
    public const TYPE_OCTET = 'oct';
    public const TYPE_RSA = 'RSA';

    /**
     * Returns value of parameter if he exists.
     */
    public function get(string $parameter): string;

    /**
     * Checks if a parameter exists in a key.
     */
    public function has(string $parameter): bool;

    /**
     * Returns the type of the key.
     */
    public function getType(): string;

    /**
     * Returns all parameters for this key or array key format.
     */
    public function all(): array;
}
