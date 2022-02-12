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

namespace RM\Standard\Jwt\Handler;

use RM\Standard\Jwt\Generator\IssuerGenerator;
use RM\Standard\Jwt\Validator\Property\IssuerValidator;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class IssuerClaimHandler extends DelegatingPropertyHandler
{
    public function __construct(string $issuer)
    {
        $generator = new IssuerGenerator($issuer);
        $validator = new IssuerValidator($issuer);

        parent::__construct($generator, $validator);
    }
}
