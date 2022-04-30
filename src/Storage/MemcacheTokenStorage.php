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

namespace RM\Standard\Jwt\Storage;

use InvalidArgumentException;
use Memcache;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class MemcacheTokenStorage implements TokenStorageInterface
{
    private Memcache $memcache;

    public function __construct(string $host, int $port = 11211)
    {
        // @codeCoverageIgnoreStart
        if (!class_exists(Memcache::class)) {
            $message = 'Memcache class does not exist. You need the memcache php extension to use this storage.';

            throw new InvalidArgumentException($message);
        }
        // @codeCoverageIgnoreEnd

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
