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

use Predis\Client;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class RedisTokenStorage implements TokenStorageInterface
{
    private Client $redis;

    public function __construct(string $dsn)
    {
        $this->redis = new Client($dsn);
    }

    public function has(string $tokenId): bool
    {
        return $this->redis->get($tokenId) === $tokenId;
    }

    public function put(string $tokenId, int $duration): void
    {
        $this->redis->set($tokenId, $tokenId);
        $this->redis->expire($tokenId, $duration);
    }

    public function revoke(string $tokenId): void
    {
        $this->redis->del([$tokenId]);
    }

    public static function createFromParameters(
        string $host = '127.0.0.1',
        int $port = 6379,
        int $database = 0,
        float $timeout = 0.0
    ): self {
        $dsn = sprintf('redis://%s:%d/%d?timeout=%f', $host, $port, $database, $timeout);
        return new static($dsn);
    }
}
