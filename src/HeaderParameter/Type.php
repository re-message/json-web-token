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

namespace RM\Standard\Jwt\HeaderParameter;

use RM\Standard\Jwt\Token\AbstractProperty;

/**
 * Type of token, by default is `JWT`.
 * If you use some token types, you can override this claim.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class Type extends AbstractProperty implements HeaderParameterInterface
{
    public const NAME = 'typ';

    public function getName(): string
    {
        return self::NAME;
    }
}
