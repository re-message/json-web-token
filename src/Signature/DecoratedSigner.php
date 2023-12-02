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

namespace RM\Standard\Jwt\Signature;

use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Signature\SignatureToken as Token;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @codeCoverageIgnore
 */
abstract class DecoratedSigner implements SignerInterface
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
