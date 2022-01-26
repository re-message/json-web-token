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

namespace RM\Standard\Jwt\Service\Signer;

use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Token\SignatureToken as Token;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class DecoratedSigner implements SignerInterface
{
    private SignerInterface $signer;

    public function __construct(SignerInterface $signer)
    {
        $this->signer = $signer;
    }

    public function sign(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): Token
    {
        return $this->signer->sign($token, $algorithm, $key);
    }

    public function verify(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): bool
    {
        return $this->signer->verify($token, $algorithm, $key);
    }
}
