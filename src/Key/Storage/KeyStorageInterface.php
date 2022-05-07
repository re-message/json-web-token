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

namespace RM\Standard\Jwt\Key\Storage;

use RM\Standard\Jwt\Exception\KeyNotFoundException;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
interface KeyStorageInterface
{
    /**
     * Get key from storage by id or throw exception.
     *
     * @throws KeyNotFoundException
     */
    public function get(int|string $id): KeyInterface;

    /**
     * Find key from storage by id.
     */
    public function find(int|string $id): KeyInterface|null;

    /**
     * Checks if key exists in storage.
     */
    public function has(int|string $id): bool;

    /**
     * Add key to storage.
     */
    public function add(KeyInterface $key): void;

    /**
     * Add keys to storage.
     *
     * @param iterable<KeyInterface> $keys
     */
    public function addAll(iterable $keys): void;
}
