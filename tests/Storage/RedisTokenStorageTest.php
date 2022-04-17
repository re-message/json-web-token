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

namespace RM\Standard\Jwt\Tests\Storage;

use Laminas\Math\Rand;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Predis\Connection\ConnectionException;
use RM\Standard\Jwt\Storage\RedisTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;

/**
 * @coversDefaultClass \RM\Standard\Jwt\Storage\RedisTokenStorage
 *
 * @internal
 */
class RedisTokenStorageTest extends TestCase
{
    private static TokenStorageInterface $storage;
    private static string $someTokenId;

    public static function setUpBeforeClass(): void
    {
        $host = $_ENV['REDIS_HOST'];
        $port = (int) $_ENV['REDIS_PORT'];

        if (!self::isRedisAvailable($host, $port)) {
            self::markTestIncomplete('Redis server is not available');
        }

        self::$storage = RedisTokenStorage::createFromParameters($host, $port);
        self::$someTokenId = Rand::getString(256);
    }

    /**
     * @covers ::put
     */
    public function testPut(): void
    {
        self::$storage->put(self::$someTokenId, 60);
        self::assertTrue(self::$storage->has(self::$someTokenId));
        self::assertFalse(self::$storage->has(Rand::getString(256)));
    }

    /**
     * @covers ::revoke
     * @depends testPut
     */
    public function testRevoke(): void
    {
        self::assertTrue(self::$storage->has(self::$someTokenId));
        self::$storage->revoke(self::$someTokenId);
        self::assertFalse(self::$storage->has(self::$someTokenId));
    }

    /**
     * @noinspection PhpRedundantCatchClauseInspection
     */
    private static function isRedisAvailable(string $host, int $port): bool
    {
        try {
            $dsn = sprintf('redis://%s:%d', $host, $port);
            $client = new Client($dsn);
            $client->connect();

            return true;
        } catch (ConnectionException) {
            return false;
        }
    }
}
