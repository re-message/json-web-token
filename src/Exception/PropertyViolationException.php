<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2021 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Exception;

use RM\Standard\Jwt\Handler\AbstractPropertyHandler;
use Throwable;

/**
 * Class ViolationException
 * This class isn't related to symfony validation package.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class PropertyViolationException extends InvalidPropertyException
{
    private AbstractPropertyHandler $claimHandler;

    public function __construct(string $message, AbstractPropertyHandler $claimHandler, Throwable $previous = null)
    {
        parent::__construct($message, $claimHandler->getPropertyName(), $previous);
        $this->claimHandler = $claimHandler;
    }

    public function getClaimHandler(): AbstractPropertyHandler
    {
        return $this->claimHandler;
    }
}