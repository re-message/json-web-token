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

namespace RM\Standard\Jwt\Tests\Key\Transformer\PublicKey;

use PHPUnit\Framework\TestCase;
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
use RM\Standard\Jwt\Key\Transformer\PublicKey\RsaPublicKeyTransformer;

/**
 * @covers \RM\Standard\Jwt\Key\Transformer\PublicKey\RsaPublicKeyTransformer
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @internal
 */
class RsaPublicKeyTransformerTest extends TestCase
{
    /**
     * @dataProvider provideKey
     */
    public function testTransform(KeyInterface $private, KeyInterface $public): void
    {
        $transformer = new RsaPublicKeyTransformer();
        $actual = $transformer->transform($private);

        self::assertEquals($public, $actual);
    }

    public function provideKey(): iterable
    {
        $private = new Key(
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

        $public = new Key(
            [
                new Type(Type::RSA),
                new Modulus('0YRhe1-ANGVZRIOfeFpjg1VVCIoj0LNOGi5r34PEm6Kx7LOYEF7tGiHVE-p2-a04eYQ9sn6RdliFujPM9FsUYcInIF8IvNB9Wiz3-pRDzy7zdvRIUDYwaZBZACVG0vLroSRHisNHBOPYPNHgQReAQqki-jxUGz_f6wcsXcRKACE'),
                new PublicExponent('AQAB'),
            ],
        );

        yield [$private, $public];
    }
}
