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

namespace RM\Standard\Jwt\Tests\Algorithm;

use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\AlgorithmManager;
use RM\Standard\Jwt\Algorithm\Signature\None;
use RM\Standard\Jwt\Exception\AlgorithmNotFoundException;
use stdClass;
use TypeError;

/**
 * @internal
 */
#[CoversClass(AlgorithmManager::class)]
class AlgorithmManagerTest extends TestCase
{
    private AlgorithmManager $manager;

    #[Override]
    protected function setUp(): void
    {
        $none = new None();

        $this->manager = new AlgorithmManager();
        $this->manager->put($none);
    }

    public function testValidConstructor(): void
    {
        $manager = new AlgorithmManager([new None()]);
        self::assertInstanceOf(AlgorithmManager::class, $manager);
    }

    public function testInvalidConstructor(): void
    {
        $this->expectException(TypeError::class);

        $unsupportedClass = new stdClass();
        $algorithms = [$unsupportedClass];

        /** @noinspection PhpParamsInspection */
        /** @psalm-suppress InvalidArgument */
        new AlgorithmManager($algorithms);
    }

    public function testValidGet(): void
    {
        self::assertInstanceOf(None::class, $this->manager->get('none'));
    }

    public function testInvalidGet(): void
    {
        $this->expectException(AlgorithmNotFoundException::class);
        $this->manager->get('HS256');
    }

    public function testHas(): void
    {
        self::assertTrue($this->manager->has('none'));
        self::assertFalse($this->manager->has('HS256'));
    }

    public function testRemove(): void
    {
        self::assertTrue($this->manager->has('none'));
        $this->manager->remove('none');
        self::assertFalse($this->manager->has('none'));
    }

    public function testPut(): void
    {
        $some = new Some();
        self::assertFalse($this->manager->has($some->name()));
        $this->manager->put($some);
        self::assertTrue($this->manager->has($some->name()));
    }
}
