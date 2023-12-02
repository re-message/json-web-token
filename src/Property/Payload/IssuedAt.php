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
use RM\Standard\Jwt\Generator\IssuedAtGenerator;
use RM\Standard\Jwt\Validator\Property\IssuedAtValidator;

/**
 * Issued at time is a time in UNIX format of token creation.
 * Often a value of this claim equals a value of {@see NotBefore} claim.
 *
 * @see IssuedAtGenerator can generate value for this claim.
 * @see IssuedAtValidator can validate this claim.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class IssuedAt extends DateValueClaim
{
    public const NAME = 'iat';

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
