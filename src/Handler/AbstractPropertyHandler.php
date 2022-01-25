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

namespace RM\Standard\Jwt\Handler;

use Exception;
use InvalidArgumentException;
use RM\Standard\Jwt\Claim\ClaimInterface;
use RM\Standard\Jwt\Exception\InvalidPropertyException;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Exception\PropertyViolationException;
use RM\Standard\Jwt\HeaderParameter\HeaderParameterInterface;
use RM\Standard\Jwt\Token\PropertyBag;
use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * Class AbstractPropertyHandler
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
abstract class AbstractPropertyHandler implements TokenHandlerInterface
{
    /**
     * Returns a property class.
     *
     * @return class-string<PropertyInterface>
     */
    abstract public function getPropertyClass(): string;

    /**
     * Returns name of property to handle.
     */
    public function getPropertyName(): string
    {
        return $this->getPropertyClass()::NAME;
    }

    /**
     * @inheritDoc
     */
    final public function generate(TokenInterface $token): void
    {
        $target = $this->resolveTarget($token);
        if (!$target->has($this->getPropertyName())) {
            $property = $this->generateProperty();
            $target->set($property);
        }
    }

    /**
     * Generate value for current claim.
     */
    abstract protected function generateProperty(): PropertyInterface;

    /**
     * Checks if the passed value is valid.
     *
     * @throws PropertyViolationException
     * @throws InvalidTokenException
     */
    final public function validate(TokenInterface $token): bool
    {
        $target = $this->resolveTarget($token);
        $propertyName = $this->getPropertyName();
        if (!$target->has($propertyName)) {
            return true;
        }

        $property = $target->get($propertyName);

        try {
            if ($this->validateProperty($property) === true) {
                return true;
            }
        } catch (PropertyViolationException $e) {
            // correct exception, just throw her again
            throw $e;
        } catch (Exception $e) {
            // incorrect exception, throw ClaimViolationException with previous
            throw new PropertyViolationException('The token did not pass validation.', $this, $e);
        }

        // if no exception and result false, then just throw ClaimViolationException
        throw new PropertyViolationException('The token did not pass validation.', $this);
    }

    /**
     * Validate property.
     * Please throw instance of {@see PropertyViolationException} if this validation failed.
     * If you just return `false` or throw other exception then {@see validate()} will throw {@see PropertyViolationException} self.
     *
     * @throws PropertyViolationException
     * @throws InvalidPropertyException
     */
    abstract protected function validateProperty(PropertyInterface $property): bool;

    protected function resolveTarget(TokenInterface $token): PropertyBag
    {
        $class = $this->getPropertyClass();
        if (is_a($class, HeaderParameterInterface::class, true)) {
            return $token->getHeader();
        }

        if (is_a($class, ClaimInterface::class, true)) {
            return $token->getPayload();
        }

        $message = sprintf('Undefined property target: %s', $this->getPropertyClass());
        throw new InvalidArgumentException($message);
    }
}
