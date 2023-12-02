<?php
/*
 * This file is a part of Re Message Json Web Token implementation.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/json-web-token
 * @link      https://dev.remessage.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2023 Re Message
 * @author    Oleg Kozlov <h1karo@remessage.ru>
 * @license   Apache License 2.0
 * @license   https://legal.remessage.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Tests\Storage;

use Laminas\Math\Rand;
use Override;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Predis\Connection\ConnectionException;
use RM\Standard\Jwt\Storage\RedisTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;

/**
 * @covers \RM\Standard\Jwt\Storage\RedisTokenStorage
 *
 * @internal
 */
class RedisTokenStorageTest extends TestCase
{
    private static string $host;
    private static int $port;
    private static string $someTokenId;

    #[Override]
    public static function setUpBeforeClass(): void
    {
        self::$host = $_ENV['REDIS_HOST'];
        self::$port = (int) $_ENV['REDIS_PORT'];

        if (!self::isRedisAvailable(self::$host, self::$port)) {
            self::markTestIncomplete('Redis server is not available');
        }

        self::$someTokenId = Rand::getString(256);
    }

    public function testPut(): TokenStorageInterface
    {
        $storage = RedisTokenStorage::createFromParameters(self::$host, self::$port);

        $storage->put(self::$someTokenId, 60);
        self::assertTrue($storage->has(self::$someTokenId));
        self::assertFalse($storage->has(Rand::getString(256)));

        return $storage;
    }

    /**
     * @depends testPut
     */
    public function testRevoke(TokenStorageInterface $storage): void
    {
        self::assertTrue($storage->has(self::$someTokenId));
        $storage->revoke(self::$someTokenId);
        self::assertFalse($storage->has(self::$someTokenId));
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
