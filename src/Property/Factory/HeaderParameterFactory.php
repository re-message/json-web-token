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

namespace RM\Standard\Jwt\Property\Factory;

use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Property\Header\Custom;
use RM\Standard\Jwt\Property\Header\HeaderParameterInterface;
use RM\Standard\Jwt\Property\Header\Type;

/**
 * @template-extends AbstractPropertyFactory<HeaderParameterInterface>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class HeaderParameterFactory extends AbstractPropertyFactory
{
    public function __construct()
    {
        parent::__construct(
            [
                Algorithm::NAME => Algorithm::class,
                Type::NAME => Type::class,
            ],
            Custom::class,
        );
    }
}
