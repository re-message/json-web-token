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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Algorithm\AlgorithmManager;
use RM\Standard\Jwt\Algorithm\AlgorithmResolver;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HMAC;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3512;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @covers \RM\Standard\Jwt\Algorithm\AlgorithmResolver
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class AlgorithmResolverTest extends TestCase
{
    /**
     * @dataProvider provideAlgorithm
     *
     * @param class-string<AlgorithmInterface> $by
     */
    public function testValidAlgorithm(AlgorithmInterface $expected, string $by): void
    {
        $manager = $this->createMock(AlgorithmManager::class);
        $resolver = new AlgorithmResolver($manager);
        $token = $this->createMock(TokenInterface::class);

        $token->method('getAlgorithm')->willReturn($expected->name());
        $manager->method('get')->willReturn($expected);

        $actual = $resolver->resolve($token, $by);
        self::assertSame($expected, $actual);
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
            yield "get {$algorithm->name()} by algo" => [
                $algorithm,
                AlgorithmInterface::class,
            ];

            yield "get {$algorithm->name()} by sign algo" => [
                $algorithm,
                SignatureAlgorithmInterface::class,
            ];

            yield "get {$algorithm->name()} by hmac" => [
                $algorithm,
                HMAC::class,
            ];
        }
    }

    public function testInvalidAlgorithm(): void
    {
        $manager = $this->createMock(AlgorithmManager::class);
        $resolver = new AlgorithmResolver($manager);
        $token = $this->createMock(TokenInterface::class);

        $algorithm = new HS256();
        $token->method('getAlgorithm')->willReturn($algorithm->name());
        $manager->method('get')->willReturn($algorithm);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must implement');
        $resolver->resolve($token, Some::class);
    }
}
