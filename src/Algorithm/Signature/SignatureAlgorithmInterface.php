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

namespace RM\Standard\Jwt\Algorithm\Signature;

use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * Interface SignatureAlgorithmInterface implements Json Web Token standard for signatures (RFC 7618, section 3).
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @see https://tools.ietf.org/html/rfc7518
 */
interface SignatureAlgorithmInterface extends AlgorithmInterface
{
    /**
     * Sign input with key.
     */
    public function hash(KeyInterface $key, string $input): string;

    /**
     * Verify signature for this input and key pair.
     */
    public function verify(KeyInterface $key, string $input, string $hash): bool;
}
