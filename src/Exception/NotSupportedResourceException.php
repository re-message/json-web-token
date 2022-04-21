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

namespace RM\Standard\Jwt\Exception;

use BadMethodCallException;
use Throwable;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class NotSupportedResourceException extends BadMethodCallException implements LoaderExceptionInterface
{
    private const SUPPORTS_METHOD = 'supports';

    public function __construct(
        string $loader,
        string $resource,
        string $method,
        ?Throwable $previous = null
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
