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

namespace RM\Standard\Jwt\Generator;

use RM\Standard\Jwt\Property\Payload\Expiration;
use RM\Standard\Jwt\Token\PropertyTarget;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see Expiration
 */
class ExpirationGenerator extends AbstractDurationGenerator
{
    public function getPropertyName(): string
    {
        return Expiration::NAME;
    }

    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    public function generate(): Expiration
    {
        $value = time() + $this->getDuration();

        return new Expiration($value);
    }
}
