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

use Laminas\Math\Rand;
use ParagonIE\ConstantTime\Base64UrlSafe;
use PHPUnit\Framework\TestCase;
use RM\Standard\Jwt\Algorithm\Signature\HMAC\HS3256;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\OctetKey;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\Signer;

class SignerTest extends TestCase
{
    public function testSignAlreadySignedToken(): void
    {
        $algorithm = new HS3256();
        $key = $this->generateKey();
        $token = SignatureToken::createWithAlgorithm($algorithm);

        $signer = new Signer();
        $signed = $signer->sign($token, $algorithm, $key);

        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionMessage('signed');
        $signer->sign($signed, $algorithm, $key);
    }

    public function generateKey(): KeyInterface
    {
        return new OctetKey(Base64UrlSafe::encode(Rand::getBytes(64)));
    }
}
