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

namespace RM\Standard\Jwt\Algorithm\Signature\HMAC;

use Override;

/**
 * Class HS3256 is HMAC implementation with SHA3-256 algorithm.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
readonly class HS3256 extends HMAC
{
    #[Override]
    public function name(): string
    {
        return 'HS3256';
    }

    #[Override]
    protected function getHashAlgorithm(): string
    {
        return 'sha3-256';
    }
}
