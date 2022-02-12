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

namespace RM\Standard\Jwt\Validator\Property;

use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Property\Payload\Identifier;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;
use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\PropertyTarget;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @see Identifier
 */
class IdentifierValidator implements PropertyValidatorInterface
{
    public function __construct(
        protected readonly TokenStorageInterface $storage = new RuntimeTokenStorage(),
    ) {}

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
