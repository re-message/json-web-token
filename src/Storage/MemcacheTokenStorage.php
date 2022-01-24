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
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class MemcacheTokenStorage implements TokenStorageInterface
{
    private Memcache $memcache;

    public function __construct(string $host, int $port = 11211)
    {
        if (!class_exists(Memcache::class, false)) {
            $message = 'Memcache class does not exist. Maybe you should install memcache php extension.';
            throw new InvalidArgumentException($message);
        }

        $this->memcache = new Memcache();
        $this->memcache->addServer($host, $port);
    }

    public function has(string $tokenId): bool
    {
        return $this->memcache->get($tokenId) === $tokenId;
    }

    public function put(string $tokenId, int $duration): void
    {
        $this->memcache->set($tokenId, $tokenId, 0, $duration);
    }

    public function revoke(string $tokenId): void
    {
        $this->memcache->delete($tokenId);
    }
}
