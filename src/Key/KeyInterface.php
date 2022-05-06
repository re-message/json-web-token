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
use RM\Standard\Jwt\Key\Parameter\KeyParameterInterface;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactoryInterface;

/**
 * Interface KeyInterface implements JSON Web Key standard (RFC 7517).
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see https://datatracker.ietf.org/doc/html/rfc7517
 */
interface KeyInterface extends JsonSerializable
{
    /**
     * Returns value of parameter if he exists.
     */
    public function get(string $name): KeyParameterInterface;

    /**
     * Checks if a parameter exists in a key.
     */
    public function has(string $name): bool;

    /**
     * Set the parameter to the key.
     * The existing parameter will be overwritten.
     */
    public function set(KeyParameterInterface $parameter): void;

    /**
     * Returns the type of the key.
     */
    public function getType(): string;

    /**
     * Returns all parameters.
     *
     * @return KeyParameterInterface[]
     */
    public function getParameters(): array;

    /**
     * Returns all parameters in array format.
     */
    public function all(): array;

    /**
     * Create a thumbprint from the key.
     *
     * @see ThumbprintFactoryInterface
     * @see https://datatracker.ietf.org/doc/html/rfc7638
     */
    public function toThumbprint(ThumbprintFactoryInterface $factory = null): string;
}
