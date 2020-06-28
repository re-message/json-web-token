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

namespace RM\Standard\Jwt\Storage;

/**
 * Interface TokenIdStorage
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
interface TokenStorageInterface
{
    /**
     * Checks if token id exists in storage
     *
     * @param string $tokenId
     *
     * @return bool
     */
    public function has(string $tokenId): bool;

    /**
     * Adds token id in storage on some duration (ttl)
     *
     * @param string $tokenId
     * @param int    $duration
     */
    public function put(string $tokenId, int $duration): void;

    /**
     * Revokes token
     *
     * @param string $tokenId
     */
    public function revoke(string $tokenId): void;
}
