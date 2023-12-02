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

use RM\Standard\Jwt\Validator\Property\PropertyValidatorInterface;
use Throwable;

/**
 * This class isn't related to symfony validation package.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
