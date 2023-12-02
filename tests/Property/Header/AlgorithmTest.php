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

namespace RM\Standard\Jwt\Tests\Property\Header;

use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3512;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512;
use RM\Standard\Jwt\Property\Header\Algorithm;

/**
 * @covers \RM\Standard\Jwt\Property\Header\Algorithm
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class AlgorithmTest extends TestCase
{
    public function testName(): void
    {
        $algorithm = new Algorithm('alg');
        self::assertSame('alg', $algorithm->getName());
    }

    /**
     * @dataProvider provideAlgorithm
     */
    public function testSecondaryConstructor(AlgorithmInterface $algorithm): void
    {
        $parameter = Algorithm::fromAlgorithm($algorithm);
        self::assertSame($algorithm->name(), $parameter->getValue());
    }

    public function provideAlgorithm(): iterable
    {
        $algorithms = [
            new HS256(),
            new HS512(),
            new HS3256(),
            new HS3512(),
        ];

        foreach ($algorithms as $algorithm) {
            yield $algorithm->name() => [$algorithm];
        }
    }
}
