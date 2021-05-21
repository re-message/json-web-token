<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2021 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Claim;

/**
 * Issued at time is a time in UNIX format of token creation.
 * Often a value of this claim equals a value of {@see NotBefore} claim.
 *
 * @see IssuedAtClaimHandler The manager for this claim.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class IssuedAt extends DateValueClaim
{
    public const NAME = 'iat';

    public function getName(): string
    {
        return self::NAME;
    }
}