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
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Signature\SignatureToken as Token;

/**
 * Signs the token by algorithm and key.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
interface SignerInterface
{
    /**
     * @throws InvalidTokenException
     */
    public function sign(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): Token;

    public function verify(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): bool;
}
