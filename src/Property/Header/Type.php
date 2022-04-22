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

namespace RM\Standard\Jwt\Property\Header;

use RM\Standard\Jwt\Token\AbstractProperty;

/**
 * Type of token, by default is `JWT`.
 * If you use some token types, you can override this claim.
 *
 * @template-extends AbstractProperty<string>
 * @template-implements HeaderParameterInterface<string>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class Type extends AbstractProperty implements HeaderParameterInterface
{
    public const NAME = 'typ';

    public function getName(): string
    {
        return self::NAME;
    }
}
