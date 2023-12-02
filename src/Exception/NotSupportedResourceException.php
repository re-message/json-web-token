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

use BadMethodCallException;
use Throwable;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class NotSupportedResourceException extends BadMethodCallException implements LoaderExceptionInterface
{
    private const SUPPORTS_METHOD = 'supports';

    public function __construct(
        string $loader,
        string $resource,
        string $method,
        Throwable $previous = null
    ) {
        $message = sprintf(
            '%1$s does not support %2$s. Use %1$s::%3$s method before call %4$s.',
            $loader,
            $resource,
            self::SUPPORTS_METHOD,
            $method,
        );

        parent::__construct($message, 0, $previous);
    }
}
