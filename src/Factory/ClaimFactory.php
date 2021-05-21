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

namespace RM\Standard\Jwt\Factory;

use RM\Standard\Jwt\Claim\Audience;
use RM\Standard\Jwt\Claim\Expiration;
use RM\Standard\Jwt\Claim\Identifier;
use RM\Standard\Jwt\Claim\IssuedAt;
use RM\Standard\Jwt\Claim\Issuer;
use RM\Standard\Jwt\Claim\NotBefore;
use RM\Standard\Jwt\Claim\PrivateClaim;
use RM\Standard\Jwt\Claim\Subject;

/**
 * Class ClaimFactory
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class ClaimFactory extends AbstractFactory
{
    public function __construct()
    {
        parent::__construct(
            [
                Audience::NAME => Audience::class,
                Expiration::NAME => Expiration::class,
                Identifier::NAME => Identifier::class,
                IssuedAt::NAME => IssuedAt::class,
                Issuer::NAME => Issuer::class,
                NotBefore::NAME => NotBefore::class,
                Subject::NAME => Subject::class,
            ],
            PrivateClaim::class
        );
    }
}
