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

namespace RM\Standard\Jwt\Tests\Storage;

use Laminas\Math\Rand;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;

class RuntimeTokenStorageTest extends TestCase
{
    private static TokenStorageInterface $storage;
    private static string $someTokenId;

    public static function setUpBeforeClass(): void
    {
        self::$storage = new RuntimeTokenStorage();
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
