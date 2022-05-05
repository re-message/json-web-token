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

namespace RM\Standard\Jwt\Signature;

use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Property\Header\KeyId;
use RM\Standard\Jwt\Serializer\SignatureCompactSerializer;
use RM\Standard\Jwt\Serializer\SignatureSerializerInterface;
use RM\Standard\Jwt\Signature\SignatureToken as Token;

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

        if ($algorithm->name() !== $token->getAlgorithm()) {
            $token = $token->setAlgorithm($algorithm);
        }

        if ($key->has(Identifier::NAME)) {
            $token->getHeader()->set(KeyId::fromKey($key));
        }

        $body = $this->serializer->serialize($token, true);
        $signature = $algorithm->sign($key, $body);

        return $token->setSignature($signature);
    }

    /**
     * @throws InvalidTokenException
     */
    public function verify(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): bool
    {
        if (!$token->isSigned()) {
            throw new InvalidTokenException('The token have no signature to validate.');
        }

        $signature = $token->getSignature();
        $body = $this->serializer->serialize($token, true);

        return $algorithm->verify($key, $body, $signature);
    }
}
