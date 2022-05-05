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

namespace RM\Standard\Jwt\Algorithm\Signature;

use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * Interface SignatureAlgorithmInterface implements Json Web Token standard for signatures (RFC 7618, section 3).
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see https://datatracker.ietf.org/doc/html/rfc7518
 */
interface SignatureAlgorithmInterface extends AlgorithmInterface
{
    /**
     * Sign input with key.
     */
    public function sign(KeyInterface $key, string $input): string;

    /**
     * Verify signature for this input and key pair.
     */
    public function verify(KeyInterface $key, string $input, string $signature): bool;
}
