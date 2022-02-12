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

use RM\Standard\Jwt\Exception\InvalidPropertyException;
use RM\Standard\Jwt\Exception\PropertyViolationException;
use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\PropertyTarget;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
interface PropertyValidatorInterface
{
    /**
     * Returns name of property to validate.
     */
    public function getPropertyName(): string;

    /**
     * Returns name of property to generate.
     */
    public function getPropertyTarget(): PropertyTarget;

    /**
     * Checks if the passed value is valid.
     *
     * @throws PropertyViolationException
     * @throws InvalidPropertyException
     */
    public function validate(PropertyInterface $property): bool;
}
