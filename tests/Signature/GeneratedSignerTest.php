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
use RM\Standard\Jwt\Token\PropertyBag;
use RM\Standard\Jwt\Token\PropertyTarget;
use RM\Standard\Jwt\Token\TokenInterface;

class GeneratedSignerTest extends TestCase
{
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

            $bag = $this->getPropertyBag($generatedToken, $target);
            self::assertTrue($bag->has($property));
        }
    }

    private function getPropertyBag(TokenInterface $token, PropertyTarget $target): PropertyBag
    {
        return match ($target) {
            PropertyTarget::HEADER => $token->getHeader(),
            PropertyTarget::PAYLOAD => $token->getPayload(),
        };
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
