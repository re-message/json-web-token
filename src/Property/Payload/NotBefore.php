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

/**
 * Not before time is a time in UNIX format before which the token is not valid.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @see NotBeforeClaimHandler The manager for this claim.
 */
class NotBefore extends DateValueClaim
{
    public const NAME = 'nbf';

    public function getName(): string
    {
        return self::NAME;
    }
}
