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

namespace RM\Standard\Jwt\Exception;

use Throwable;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class IncorrectPropertyTypeException extends InvalidPropertyException
{
    public function __construct(string $expected, string $got, string $claim, Throwable $previous = null)
    {
        $message = sprintf('Claim `%s` must be a %s, got %s.', $claim, $expected, $got);

        parent::__construct($message, $claim, $previous);
    }
}
