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

use RM\Standard\Jwt\Generator\IssuerGenerator;
use RM\Standard\Jwt\Token\AbstractProperty;
use RM\Standard\Jwt\Validator\Property\IssuerValidator;

/**
 * Issuer is a unique identity of token generator server, authentication server or security server.
 * You can set this claim to check where token generated.
 * It is maybe helps you, if you use several servers
 * with own token id {@see Identifier} cache server {@see TokenStorageInterface}.
 * We recommend set up this claim.
 *
 * @template-extends AbstractProperty<string>
 * @template-implements ClaimInterface<string>
 *
 * @see IssuerGenerator can generate value for this claim.
 * @see IssuerValidator can validate this claim.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class Issuer extends AbstractProperty implements ClaimInterface
{
    public const NAME = 'iss';

    public function getName(): string
    {
        return self::NAME;
    }
}
