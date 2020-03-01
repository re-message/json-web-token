<?php
/**
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://gitlab.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Security\Jwt\Tests\Storage;

use Laminas\Math\Rand;
use PHPUnit\Framework\TestCase;
use RM\Security\Jwt\Storage\RedisTokenStorage;
use RM\Security\Jwt\Storage\TokenStorageInterface;

class RedisTokenStorageTest extends TestCase
{
    private static TokenStorageInterface $storage;
    private static string $someTokenId;

    public static function setUpBeforeClass(): void
    {
        $redisHost = defined('REDIS_HOST') ? REDIS_HOST : '127.0.0.1';

        self::$storage = RedisTokenStorage::createFromParameters($redisHost);
        self::$someTokenId = Rand::getString(256);
    }

    public function testPut(): void
    {
        self::$storage->put(self::$someTokenId, 60);
        $this->assertTrue(self::$storage->has(self::$someTokenId));
        $this->assertFalse(self::$storage->has(Rand::getString(256)));
    }

    public function testRevoke(): void
    {
        self::$storage->revoke(self::$someTokenId);
        $this->assertFalse(self::$storage->has(self::$someTokenId));
    }
}
