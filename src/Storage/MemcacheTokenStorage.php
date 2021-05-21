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

namespace RM\Standard\Jwt\Storage;

use InvalidArgumentException;
use Memcache;

/**
 * Class MemcacheTokenStorage
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class MemcacheTokenStorage implements TokenStorageInterface
{
    private Memcache $memcache;

    public function __construct(string $host, int $port = 11211)
    {
        if (!class_exists(Memcache::class, false)) {
            throw new InvalidArgumentException(
                'Memcache class is not found. Maybe you should install memcache php extension.'
            );
        }

        $this->memcache = new Memcache();
        $this->memcache->addServer($host, $port);
    }

    /**
     * Checks if token id exists in storage
     *
     * @param string $tokenId
     *
     * @return bool
     */
    public function has(string $tokenId): bool
    {
        return $this->memcache->get($tokenId) === $tokenId;
    }

    /**
     * Adds token id in storage on some duration (ttl)
     *
     * @param string $tokenId
     * @param int    $duration
     */
    public function put(string $tokenId, int $duration): void
    {
        $this->memcache->set($tokenId, $tokenId, 0, $duration);
    }

    /**
     * Revokes token
     *
     * @param string $tokenId
     */
    public function revoke(string $tokenId): void
    {
        $this->memcache->delete($tokenId);
    }
}
