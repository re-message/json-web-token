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

use Override;
use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Exception\IssuedAtViolationException;
use RM\Standard\Jwt\Property\Payload\IssuedAt;
use RM\Standard\Jwt\Property\PropertyInterface;
use RM\Standard\Jwt\Property\PropertyTarget;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see IssuedAt
 */
class IssuedAtValidator extends AbstractLeewayValidator
{
    #[Override]
    public function getPropertyName(): string
    {
        return IssuedAt::NAME;
    }

    #[Override]
    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    #[Override]
    public function validate(PropertyInterface $property): bool
    {
        if (!$property instanceof IssuedAt) {
            $message = sprintf('%s can handle only %s.', static::class, IssuedAt::class);

            throw new UnexpectedValueException($message);
        }

        $value = $property->getValue();
        if (!is_int($value)) {
            $name = $this->getPropertyName();
            $valueType = gettype($value);

            throw new IncorrectPropertyTypeException('integer', $valueType, $name);
        }

        if (time() < $value - $this->getLeeway()) {
            throw new IssuedAtViolationException($this);
        }

        return true;
    }
}
