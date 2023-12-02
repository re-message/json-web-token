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

namespace RM\Standard\Jwt\Property\Factory;

use RM\Standard\Jwt\Property\Payload\Audience;
use RM\Standard\Jwt\Property\Payload\ClaimInterface;
use RM\Standard\Jwt\Property\Payload\Expiration;
use RM\Standard\Jwt\Property\Payload\Identifier;
use RM\Standard\Jwt\Property\Payload\IssuedAt;
use RM\Standard\Jwt\Property\Payload\Issuer;
use RM\Standard\Jwt\Property\Payload\NotBefore;
use RM\Standard\Jwt\Property\Payload\PrivateClaim;
use RM\Standard\Jwt\Property\Payload\Subject;

/**
 * @template-extends AbstractPropertyFactory<ClaimInterface>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ClaimFactory extends AbstractPropertyFactory
{
    public const DEFAULT_CLASS_MAP = [
        Audience::NAME => Audience::class,
        Expiration::NAME => Expiration::class,
        Identifier::NAME => Identifier::class,
        IssuedAt::NAME => IssuedAt::class,
        Issuer::NAME => Issuer::class,
        NotBefore::NAME => NotBefore::class,
        Subject::NAME => Subject::class,
    ];

    /**
     * @param array<string, class-string<ClaimInterface>> $classMap
     */
    public function __construct(array $classMap = [])
    {
        $classMap = array_merge(self::DEFAULT_CLASS_MAP, $classMap);

        parent::__construct($classMap, PrivateClaim::class);
    }
}
