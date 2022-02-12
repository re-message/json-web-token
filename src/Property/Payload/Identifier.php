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

namespace RM\Standard\Jwt\Property\Payload;

use RM\Standard\Jwt\Generator\IdentifierGenerator;
use RM\Standard\Jwt\Token\AbstractProperty;
use RM\Standard\Jwt\Validator\Property\IdentifierValidator;

/**
 * Token identifier is unique sequence to provide revoke functional.
 * We recommend to set this claim.
 *
 * @see IdentifierGenerator can generate value for this claim.
 * @see IdentifierValidator can validate this claim.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class Identifier extends AbstractProperty implements ClaimInterface
{
    public const NAME = 'jti';

    public function getName(): string
    {
        return self::NAME;
    }
}
