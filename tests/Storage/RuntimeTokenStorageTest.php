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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;

/**
 * @internal
 */
#[CoversClass(RuntimeTokenStorage::class)]
class RuntimeTokenStorageTest extends TestCase
{
    private static string $someTokenId;

    #[Override]
    public static function setUpBeforeClass(): void
    {
        self::$someTokenId = Rand::getString(256);
    }

    public function testPut(): TokenStorageInterface
    {
        $storage = new RuntimeTokenStorage();

        $storage->put(self::$someTokenId, 60);
        self::assertTrue($storage->has(self::$someTokenId));
        self::assertFalse($storage->has(Rand::getString(256)));

        return $storage;
    }

    #[Depends('testPut')]
    public function testRevoke(TokenStorageInterface $storage): void
    {
        $storage->revoke(self::$someTokenId);
        self::assertFalse($storage->has(self::$someTokenId));
    }
}
