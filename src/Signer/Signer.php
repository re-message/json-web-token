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

namespace RM\Standard\Jwt\Signer;

use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Serializer\SignatureCompactSerializer;
use RM\Standard\Jwt\Serializer\SignatureSerializerInterface;
use RM\Standard\Jwt\Token\SignatureToken as Token;

class Signer implements SignerInterface
{
    private SignatureSerializerInterface $serializer;

    public function __construct(SignatureSerializerInterface $serializer = null)
    {
        $this->serializer = $serializer ?? new SignatureCompactSerializer();
    }

    public function sign(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): Token
    {
        if ($token->isSigned()) {
            throw new InvalidTokenException('This token already signed');
        }

        $body = $this->serializer->serialize($token, true);
        $signature = $algorithm->hash($key, $body);

        return $token->setSignature($signature);
    }

    public function verify(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): bool
    {
        $body = $this->serializer->serialize($token, true);

        return $algorithm->verify($key, $body, $token->getSignature());
    }
}
