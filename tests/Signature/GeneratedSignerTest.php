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

namespace RM\Standard\Jwt\Tests\Signature;

use Generator;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Generator\ExpirationGenerator;
use RM\Standard\Jwt\Generator\IssuedAtGenerator;
use RM\Standard\Jwt\Generator\IssuerGenerator;
use RM\Standard\Jwt\Generator\NotBeforeGenerator;
use RM\Standard\Jwt\Generator\PropertyGeneratorInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Signature\GeneratedSigner;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\SignerInterface;
use stdClass;
use TypeError;

/**
 * @covers \RM\Standard\Jwt\Signature\GeneratedSigner
 *
 * @internal
 */
class GeneratedSignerTest extends TestCase
{
    public function testInvalidConstructorUsage(): void
    {
        $signer = $this->createMock(SignerInterface::class);

        $this->expectException(TypeError::class);
        new GeneratedSigner($signer, [new stdClass()]);
    }

    public function testTokenCloningOnSign(): GeneratedSigner
    {
        $algorithm = $this->createMock(SignatureAlgorithmInterface::class);
        $algorithm->method('name')->willReturn('algo');
        $key = $this->createMock(KeyInterface::class);
        $token = SignatureToken::createWithAlgorithm($algorithm);

        $signer = $this->createMock(SignerInterface::class);
        $signer->method('sign')->willReturnArgument(0);
        $generatedSigner = new GeneratedSigner($signer);

        $generatedToken = $generatedSigner->sign($token, $algorithm, $key);

        self::assertNotSame($token, $generatedToken);

        return $generatedSigner;
    }

    /**
     * @depends      testTokenCloningOnSign
     *
     * @dataProvider providePropertyGenerators
     *
     * @param PropertyGeneratorInterface[] $generators
     */
    public function testPropertyGeneration(array $generators, GeneratedSigner $signer): void
    {
        foreach ($generators as $generator) {
            $signer->pushGenerator($generator);
        }

        $algorithm = $this->createMock(SignatureAlgorithmInterface::class);
        $algorithm->method('name')->willReturn('algo');
        $key = $this->createMock(KeyInterface::class);
        $token = SignatureToken::createWithAlgorithm($algorithm);

        $generatedToken = $signer->sign($token, $algorithm, $key);

        foreach ($generators as $generator) {
            $target = $generator->getPropertyTarget();
            $property = $generator->getPropertyName();

            $bag = $target->getBag($generatedToken);
            self::assertTrue($bag->has($property));
        }
    }

    public function providePropertyGenerators(): Generator
    {
        yield 'issuer only' => [[new IssuerGenerator('issuer')]];

        yield 'expiration only' => [[new ExpirationGenerator()]];

        yield 'issued at only' => [[new IssuedAtGenerator()]];

        yield 'not before only' => [[new NotBeforeGenerator()]];

        yield 'all generators' => [
            [
                new IssuerGenerator('all'),
                new ExpirationGenerator(),
                new IssuedAtGenerator(),
                new NotBeforeGenerator(),
            ],
        ];
    }
}
