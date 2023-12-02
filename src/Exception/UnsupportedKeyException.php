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

use RM\Standard\Jwt\Key\Factory\KeyFactoryInterface;
use Throwable;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class UnsupportedKeyException extends InvalidKeyException
{
    /**
     * @param class-string<KeyFactoryInterface> $factory
     */
    public function __construct(?string $type, string $factory, ?Throwable $previous = null)
    {
        $type ??= 'unknown';
        $message = sprintf('Key with type "%s" does not supported by %s.', $type, $factory);

        parent::__construct($message, $previous);
    }
}
