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

use RM\Standard\Jwt\Validator\Property\PropertyValidatorInterface;
use Throwable;

/**
 * This class isn't related to symfony validation package.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class PropertyViolationException extends InvalidPropertyException
{
    public function __construct(
        string $message,
        private readonly PropertyValidatorInterface $validator,
        Throwable $previous = null
    ) {
        $propertyName = $validator->getPropertyName();
        parent::__construct($message, $propertyName, $previous);
    }

    public function getValidator(): PropertyValidatorInterface
    {
        return $this->validator;
    }
}
