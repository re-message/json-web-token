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

namespace RM\Standard\Jwt\Tests\Algorithm\Signature\HMAC;

use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3512;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS512;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Key\OctetKey;

/**
 * @coversDefaultClass \RM\Standard\Jwt\Algorithm\Signature\HMAC\HMAC
 *
 * @internal
 */
class HMACTest extends TestCase
{
    private OctetKey $key;

    protected function setUp(): void
    {
        $this->key = new OctetKey(
            'zi8zioLYkOwX0i2n3iEi2a2oAFJpiqPxd-_qcCewX07lz6yRmLxMr2wUixlrqeiBhQdaU1ugHZv55T5PsEqeOg'
        );
    }

    /**
     * @covers ::allowedKeyTypes
     * @dataProvider provideAlgorithms
     */
    public function testOctetKeyIsAllowed(SignatureAlgorithmInterface $algorithm): void
    {
        self::assertContains($this->key->getType(), $algorithm->allowedKeyTypes());
    }

    /**
     * @covers ::hash
     * @covers ::verify
     * @dataProvider provideAlgorithms
     */
    public function testHash(SignatureAlgorithmInterface $algorithm, string $input, string $expects): void
    {
        $hash = $algorithm->hash($this->key, $input);
        self::assertTrue(hash_equals($expects, $hash));

        self::assertTrue($algorithm->verify($this->key, $input, $hash));
        self::assertFalse($algorithm->verify($this->key, 'bad-input', $hash));
        self::assertFalse($algorithm->verify($this->key, $input, 'bad-hash'));
        self::assertFalse($algorithm->verify($this->key, 'bad-input', 'bad-hash'));
    }

    public function provideAlgorithms(): iterable
    {
        yield [
            new HS256(),
            'input',
            hex2bin('f54fd0731daba5ffb087794dae7605586806174716a0707e0870271db629856a'),
        ];

        yield [
            new HS512(),
            'input',
            hex2bin(
                '523fe3160ec40b07a19ce0171dd6bc0be0520785c5d190679606c49fbaa0aae58ebf57895b53f34aa0320863bfcebfa32733e13b4f66ebe99e40fd7cc88ae76f'
            ),
        ];

        yield [
            new HS3256(),
            'input',
            hex2bin('ef7ddbdc8e63d45a8efa7fa88f3cd6be38611ef87d9cf2f36c8958949d95c3f7'),
        ];

        yield [
            new HS3512(),
            'input',
            hex2bin(
                '0d86e47c72f02d947489a64a5ac9148483575caeae38acb935699ddd676e0c72d849ed9aef0cc02c3a8b8b1c854be7227921959d50be1a4c307b03f0db33034d'
            ),
        ];
    }
}
