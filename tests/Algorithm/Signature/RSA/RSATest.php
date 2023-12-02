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

namespace RM\Standard\Jwt\Tests\Algorithm\Signature\RSA;

use Laminas\Math\Rand;
use Override;
use phpseclib3\Crypt\RSA as CryptRSA;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\RSA\PS256;
use RM\Standard\Jwt\Algorithm\Signature\RSA\PS512;
use RM\Standard\Jwt\Algorithm\Signature\RSA\RS256;
use RM\Standard\Jwt\Algorithm\Signature\RSA\RS512;
use RM\Standard\Jwt\Algorithm\Signature\RSA\RSA;
use RM\Standard\Jwt\Key\Key;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\FirstCoefficient;
use RM\Standard\Jwt\Key\Parameter\FirstFactorExponent;
use RM\Standard\Jwt\Key\Parameter\FirstPrimeFactor;
use RM\Standard\Jwt\Key\Parameter\Modulus;
use RM\Standard\Jwt\Key\Parameter\PrivateExponent;
use RM\Standard\Jwt\Key\Parameter\PublicExponent;
use RM\Standard\Jwt\Key\Parameter\SecondFactorExponent;
use RM\Standard\Jwt\Key\Parameter\SecondPrimeFactor;
use RM\Standard\Jwt\Key\Parameter\Type;
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

    #[Override]
    protected function setUp(): void
    {
        $this->transformer = new RsaSecLibTransformer();
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
        yield 'RS256' => [new RS256(), 'sha256', CryptRSA::SIGNATURE_PKCS1];

        yield 'RS512' => [new RS512(), 'sha512', CryptRSA::SIGNATURE_PKCS1];

        yield 'PS256' => [new PS256(), 'sha256', CryptRSA::SIGNATURE_PSS];

        yield 'PS512' => [new PS512(), 'sha512', CryptRSA::SIGNATURE_PSS];
    }

    /**
     * @dataProvider provideStaticRsa
     */
    public function testStaticSigning(
        RSA $rsa,
        KeyInterface $key,
        string $message,
        string $signature,
    ): void {
        $actual = $rsa->sign($key, $message);

        self::assertTrue($rsa->verify($key, $message, $actual));
        self::assertSame($signature, $actual);
    }

    public function provideStaticRsa(): iterable
    {
        $key = new Key(
            [
                new Type(Type::RSA),
                new Modulus('0YRhe1-ANGVZRIOfeFpjg1VVCIoj0LNOGi5r34PEm6Kx7LOYEF7tGiHVE-p2-a04eYQ9sn6RdliFujPM9FsUYcInIF8IvNB9Wiz3-pRDzy7zdvRIUDYwaZBZACVG0vLroSRHisNHBOPYPNHgQReAQqki-jxUGz_f6wcsXcRKACE'),
                new PublicExponent('AQAB'),
                new PrivateExponent('A9_q3Zk6ib2GFRpKDLO_O2KMnAfR-b4XJ6zMGeoZ7Lbpi3MW0Nawk9ckVaX0ZVGqxbSIX5Cvp_yjHHpww-QbUFrw_gCjLiiYjM9E8C3uAF5AKJ0r4GBPl4u8K4bpbXeSxSB60_wPQFiQAJVcA5xhZVzqNuF3EjuKdHsw-dk-dPE'),
                new FirstPrimeFactor('7m1_5VRhYA_8WLnIc-emHO1R1_ufAVJDUnsEs8CUW3LnqpJ10VsI_e0l6qZ2Hyqmau_HZxBddElrK_zTCovu1w'),
                new SecondPrimeFactor('4PVqDv_OZAbWr4syXZNv_Mpl4r5suzYMMUD9U8B2JIRnrhmGZPzLx23N9J4hEJ-Xh8tSKVc80jOkrvGlSv-Bxw'),
                new FirstFactorExponent('aTOtjA3YTV-gU7Hdza53sCnSw_8FYLrgc6NOJtYhX9xqdevbyn1lkU0zPr8mPYg_F84m6MXixm2iuSz8HZoyzw'),
                new SecondFactorExponent('Ri2paYZ5_5B2lwroqnKdZBJMGKFpUDn7Mb5hiSgocxnvMkv6NjT66Xsi3iYakJII9q8CMa1qZvT_cigmdbAh7w'),
                new FirstCoefficient('QNXyoizuGEltiSaBXx4H29EdXNYWDJ9SS5f070BRbAIldqRh3rcNvpY6BKJqFapda1DjdcncZECMizT_GMrc1w'),
            ],
        );
        $message = '883d43affb811fc653b67c38203d4f206d1b838c4714b6b';

        yield 'RS256' => [
            new RS256(),
            $key,
            $message,
            hex2bin('41c807d2d6a21a10b459359048b3353d65ccd2065263eaade560319b20a537593d0bb1a7fc87732cd168d9d9d32fa39dcb6f3366a256b921e42f8b2d871f0221c1a281d71635c47d5b952db989d4426b6c18563f69245d08689ffb854194f00023a32a5132bac2d721d14b3747aae6452bfe7e3e6869ed54555f826d68091459'),
        ];

        yield 'RS512' => [
            new RS512(),
            $key,
            $message,
            hex2bin('1f579a5bda9a9dd7754aad3c570710a34638f1aa0743120ba05ea03e7c4fc05aab698f70fffaaee8f4c0c7359ad20b12a2f5006ae5dedaf918e1243656de10748d2f5bcf9849ee8580fb5d303d52d76271961d8e46d8df670f23dff655de84e0bb7cec1a06bfe51ca8a6aa5e9ad02aa77938bfa839ebad8d1adec5783c3ab5b4'),
        ];
    }
}
