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

namespace RM\Standard\Jwt\Exception;

use RuntimeException;
use Throwable;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class KeyNotFoundException extends RuntimeException
{
    public function __construct(int|string $id, ?Throwable $previous = null)
    {
        $message = sprintf('Key with id "%s" not found in the storage.', $id);

        parent::__construct($message, 0, $previous);
    }
}
