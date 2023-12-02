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

use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Property\Payload\Identifier;
use RM\Standard\Jwt\Property\PropertyInterface;
use RM\Standard\Jwt\Property\PropertyTarget;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see Identifier
 */
class IdentifierValidator implements PropertyValidatorInterface
{
    public function __construct(
        protected readonly TokenStorageInterface $storage = new RuntimeTokenStorage(),
    ) {
    }

    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    public function validate(PropertyInterface $property): bool
    {
        if (!$property instanceof Identifier) {
            $message = sprintf('%s can handle only %s.', static::class, Identifier::class);

            throw new UnexpectedValueException($message);
        }

        $value = $property->getValue();
        if (!is_string($value)) {
            $name = $this->getPropertyName();
            $valueType = gettype($value);

            throw new IncorrectPropertyTypeException('string', $valueType, $name);
        }

        return $this->storage->has($value);
    }

    public function getPropertyName(): string
    {
        return Identifier::NAME;
    }
}
