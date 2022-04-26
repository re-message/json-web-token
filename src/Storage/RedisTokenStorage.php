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
use Predis\Client;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class RedisTokenStorage implements TokenStorageInterface
{
    private Client $redis;

    public function __construct(string $dsn)
    {
        // @codeCoverageIgnoreStart
        if (!class_exists(Client::class, false)) {
            $message = 'Redis client class is not found. You need the predis/predis package to use this storage.';

            throw new InvalidArgumentException($message);
        }
        // @codeCoverageIgnoreEnd

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
    ): static {
        $dsn = sprintf('redis://%s:%d/%d?timeout=%f', $host, $port, $database, $timeout);

        return new static($dsn);
    }
}
