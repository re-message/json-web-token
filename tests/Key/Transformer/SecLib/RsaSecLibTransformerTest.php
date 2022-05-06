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

namespace RM\Standard\Jwt\Tests\Key\Transformer\SecLib;

use phpseclib3\Crypt\RSA;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Key\Factory\KeyFactoryInterface;
use RM\Standard\Jwt\Key\Factory\RsaKeyFactory;
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

/**
 * @covers \RM\Standard\Jwt\Key\Transformer\SecLib\AbstractSecLibTransformer
 * @covers \RM\Standard\Jwt\Key\Transformer\SecLib\RsaSecLibTransformer
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class RsaSecLibTransformerTest extends TestCase
{
    /**
     * @dataProvider provideKey
     */
    public function testTransform(KeyInterface $jwk, RSA $key): void
    {
        $factory = $this->createMock(KeyFactoryInterface::class);
        $factory->expects(self::never())->method('create');
        $transformer = new RsaSecLibTransformer($factory);

        $actual = $transformer->transform($jwk, RSA::class);
        self::assertEquals(
            $key->toString('raw'),
            $actual->toString('raw'),
        );
    }

    /**
     * @dataProvider provideKey
     */
    public function testReverseTransform(KeyInterface $jwk, RSA $key): void
    {
        $factory = new RsaKeyFactory();
        $transformer = new RsaSecLibTransformer($factory);

        $actual = $transformer->reverseTransform($key);
        self::assertEquals(
            $jwk->all(),
            $actual->all(),
        );
    }

    public function provideKey(): iterable
    {
        $cert = '-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQDRhGF7X4A0ZVlEg594WmODVVUIiiPQs04aLmvfg8SborHss5gQ
Xu0aIdUT6nb5rTh5hD2yfpF2WIW6M8z0WxRhwicgXwi80H1aLPf6lEPPLvN29EhQ
NjBpkFkAJUbS8uuhJEeKw0cE49g80eBBF4BCqSL6PFQbP9/rByxdxEoAIQIDAQAB
AoGAA9/q3Zk6ib2GFRpKDLO/O2KMnAfR+b4XJ6zMGeoZ7Lbpi3MW0Nawk9ckVaX0
ZVGqxbSIX5Cvp/yjHHpww+QbUFrw/gCjLiiYjM9E8C3uAF5AKJ0r4GBPl4u8K4bp
bXeSxSB60/wPQFiQAJVcA5xhZVzqNuF3EjuKdHsw+dk+dPECQQDubX/lVGFgD/xY
uchz56Yc7VHX+58BUkNSewSzwJRbcueqknXRWwj97SXqpnYfKqZq78dnEF10SWsr
/NMKi+7XAkEA4PVqDv/OZAbWr4syXZNv/Mpl4r5suzYMMUD9U8B2JIRnrhmGZPzL
x23N9J4hEJ+Xh8tSKVc80jOkrvGlSv+BxwJAaTOtjA3YTV+gU7Hdza53sCnSw/8F
YLrgc6NOJtYhX9xqdevbyn1lkU0zPr8mPYg/F84m6MXixm2iuSz8HZoyzwJARi2p
aYZ5/5B2lwroqnKdZBJMGKFpUDn7Mb5hiSgocxnvMkv6NjT66Xsi3iYakJII9q8C
Ma1qZvT/cigmdbAh7wJAQNXyoizuGEltiSaBXx4H29EdXNYWDJ9SS5f070BRbAIl
dqRh3rcNvpY6BKJqFapda1DjdcncZECMizT/GMrc1w==
-----END RSA PRIVATE KEY-----';

        /** @var RSA\PrivateKey $privateKey */
        $privateKey = RSA::load($cert);

        $privateJWK = new Key(
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

        yield 'private key' => [$privateJWK, $privateKey];

        /** @var RSA\PublicKey $publicKey */
        $publicKey = $privateKey->getPublicKey();
        $publicJWK = new Key(
            [
                new Type(Type::RSA),
                new Modulus('0YRhe1-ANGVZRIOfeFpjg1VVCIoj0LNOGi5r34PEm6Kx7LOYEF7tGiHVE-p2-a04eYQ9sn6RdliFujPM9FsUYcInIF8IvNB9Wiz3-pRDzy7zdvRIUDYwaZBZACVG0vLroSRHisNHBOPYPNHgQReAQqki-jxUGz_f6wcsXcRKACE'),
                new PublicExponent('AQAB'),
            ],
        );

        yield 'public key' => [$publicJWK, $publicKey];
    }
}
