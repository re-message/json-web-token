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

namespace RM\Standard\Jwt\Tests\Algorithm\Signature\RSA;

use Laminas\Math\Rand;
use phpseclib3\Crypt\RSA as CryptRSA;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\RSA\PS256;
use RM\Standard\Jwt\Algorithm\Signature\RSA\PS512;
use RM\Standard\Jwt\Algorithm\Signature\RSA\RS256;
use RM\Standard\Jwt\Algorithm\Signature\RSA\RS512;
use RM\Standard\Jwt\Algorithm\Signature\RSA\RSA;
use RM\Standard\Jwt\Key\Factory\RsaKeyFactory;
use RM\Standard\Jwt\Key\Transformer\SecLib\RsaSecLibTransformer;
use RM\Standard\Jwt\Key\Transformer\SecLib\SecLibTransformerInterface;

/**
 * @covers \RM\Standard\Jwt\Algorithm\Signature\RSA\PS256
 * @covers \RM\Standard\Jwt\Algorithm\Signature\RSA\PS512
 * @covers \RM\Standard\Jwt\Algorithm\Signature\RSA\RS256
 * @covers \RM\Standard\Jwt\Algorithm\Signature\RSA\RS512
 * @covers \RM\Standard\Jwt\Algorithm\Signature\RSA\RSA
 * @covers \RM\Standard\Jwt\Algorithm\Signature\RSA\RSAPKCS1
 * @covers \RM\Standard\Jwt\Algorithm\Signature\RSA\RSAPSS
 *
 * @internal
 */
class RSATest extends TestCase
{
    private SecLibTransformerInterface $transformer;

    protected function setUp(): void
    {
        $this->transformer = $this->createRsaTransformer();
    }

    /**
     * @dataProvider provideRsa
     */
    public function testSigning(RSA $rsa, string $hash, int $padding): void
    {
        /** @var CryptRSA\PrivateKey $privateKey */
        $privateKey = CryptRSA::createKey(4096)
            ->withHash($hash)
            ->withPadding($padding)
        ;

        /** @var CryptRSA\PublicKey $publicKey */
        $publicKey = $privateKey->getPublicKey();

        $jwk = $this->transformer->reverseTransform($privateKey);

        $message = Rand::getString(32);
        $signature = $rsa->sign($jwk, $message);

        self::assertTrue($publicKey->verify($message, $signature));
        self::assertTrue($rsa->verify($jwk, $message, $signature));
    }

    public function provideRsa(): iterable
    {
        $transformer = $this->createRsaTransformer();

        yield 'RS256' => [new RS256($transformer), 'sha256', CryptRSA::SIGNATURE_PKCS1];

        yield 'RS512' => [new RS512($transformer), 'sha512', CryptRSA::SIGNATURE_PKCS1];

        yield 'PS256' => [new PS256($transformer), 'sha256', CryptRSA::SIGNATURE_PSS];

        yield 'PS512' => [new PS512($transformer), 'sha512', CryptRSA::SIGNATURE_PSS];
    }

    private function createRsaTransformer(): RsaSecLibTransformer
    {
        return new RsaSecLibTransformer(new RsaKeyFactory());
    }
}
