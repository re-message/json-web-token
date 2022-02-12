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

use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Generator\PropertyGeneratorInterface;
use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\PropertyTarget;
use RM\Standard\Jwt\Validator\Property\PropertyValidatorInterface;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class DelegatingPropertyHandler extends AbstractPropertyHandler
{
    public function __construct(
        private PropertyGeneratorInterface $generator,
        private PropertyValidatorInterface $validator
    ) {
        $hasDiffNames = $generator->getPropertyName() !== $validator->getPropertyName();
        $hasDiffTargets = $generator->getPropertyTarget() !== $validator->getPropertyTarget();
        if ($hasDiffNames || $hasDiffTargets) {
            $message = sprintf(
                'To use %s you need generator and validator with same property name and target',
                self::class
            );
            throw new UnexpectedValueException($message);
        }
    }

    public function getPropertyName(): string
    {
        return $this->generator->getPropertyName();
    }

    public function getPropertyTarget(): PropertyTarget
    {
        return $this->generator->getPropertyTarget();
    }

    protected function generateProperty(): PropertyInterface
    {
        return $this->generator->generate();
    }

    /**
     * @throws InvalidTokenException
     */
    protected function validateProperty(PropertyInterface $property): bool
    {
        return $this->validator->validate($property);
    }
}
