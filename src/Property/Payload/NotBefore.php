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

namespace RM\Standard\Jwt\Property\Payload;

use Override;
use RM\Standard\Jwt\Generator\NotBeforeGenerator;
use RM\Standard\Jwt\Validator\Property\NotBeforeValidator;

/**
 * Not before time is a time in UNIX format before which the token is not valid.
 *
 * @see NotBeforeGenerator can generate value for this claim.
 * @see NotBeforeValidator can validate this claim.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class NotBefore extends DateValueClaim
{
    public const NAME = 'nbf';

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
