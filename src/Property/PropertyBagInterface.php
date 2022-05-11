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

namespace RM\Standard\Jwt\Property;

use RM\Standard\Jwt\Exception\PropertyNotFoundException;

/**
 * @template T of PropertyInterface
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
interface PropertyBagInterface
{
    /**
     * Returns the property from the bag by name or throw exception.
     *
     * @throws PropertyNotFoundException
     *
     * @return T
     */
    public function get(string $name): PropertyInterface;

    /**
     * Returns the property from the bag by name or nul.
     *
     * @return T|null
     */
    public function find(string $name): ?PropertyInterface;

    /**
     * Checks if the property is in the bag.
     */
    public function has(string $name): bool;

    /**
     * Sets property in the bag.
     * This method overwrites an existing property.
     *
     * @param T $property
     */
    public function set(PropertyInterface $property): void;

    /**
     * Returns a list of properties.
     *
     * @return array<int, T>
     */
    public function getProperties(): array;

    /**
     * Returns properties.
     *
     * @return array<string, T>
     */
    public function toArray(): array;
}
