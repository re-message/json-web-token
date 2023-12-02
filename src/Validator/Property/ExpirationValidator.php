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

namespace RM\Standard\Jwt\Validator\Property;

use RM\Standard\Jwt\Exception\ExpirationViolationException;
use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Property\Payload\Expiration;
use RM\Standard\Jwt\Property\PropertyInterface;
use RM\Standard\Jwt\Property\PropertyTarget;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see Expiration
 */
class ExpirationValidator extends AbstractLeewayValidator
{
    public function getPropertyName(): string
    {
        return Expiration::NAME;
    }

    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    public function validate(PropertyInterface $property): bool
    {
        if (!$property instanceof Expiration) {
            $message = sprintf('%s can handle only %s.', static::class, Expiration::class);

            throw new UnexpectedValueException($message);
        }

        $value = $property->getValue();
        if (!is_int($value)) {
            throw new IncorrectPropertyTypeException('integer', gettype($value), $this->getPropertyName());
        }

        if (time() > $value + $this->getLeeway()) {
            throw new ExpirationViolationException($this);
        }

        return true;
    }
}
