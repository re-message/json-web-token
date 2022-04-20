<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Key\Storage;

use RM\Standard\Jwt\Exception\KeyNotFoundException;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
interface KeyStorageInterface
{
    /**
     * Get key from storage by id or throw exception.
     *
     * @throws KeyNotFoundException
     */
    public function get(string|int $id): KeyInterface;

    /**
     * Find key from storage by id.
     */
    public function find(string|int $id): KeyInterface|null;

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

    /**
     * Checks if key exists in storage.
     */
    public function has(string|int $id): bool;
}
