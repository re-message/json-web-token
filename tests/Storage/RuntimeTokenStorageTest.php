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

namespace RM\Standard\Jwt\Tests\Storage;

use Laminas\Math\Rand;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;

/**
 * @coversDefaultClass \RM\Standard\Jwt\Storage\RuntimeTokenStorage
 *
 * @internal
 */
class RuntimeTokenStorageTest extends TestCase
{
    private static TokenStorageInterface $storage;
    private static string $someTokenId;

    public static function setUpBeforeClass(): void
    {
        self::$storage = new RuntimeTokenStorage();
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
     */
    public function testRevoke(): void
    {
        self::$storage->revoke(self::$someTokenId);
        self::assertFalse(self::$storage->has(self::$someTokenId));
    }
}
