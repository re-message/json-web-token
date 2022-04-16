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

namespace RM\Standard\Jwt\Token;

use RM\Standard\Jwt\Exception\PropertyNotFoundException;
use RM\Standard\Jwt\Property\Payload\ClaimInterface;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class Payload extends PropertyBag
{
    /**
     * @throws PropertyNotFoundException
     */
    public function get(string $name): ClaimInterface
    {
        $property = $this->getProperty($name);
        if (!$property instanceof ClaimInterface) {
            throw new UnexpectedValueException('Expects a claim.');
        }

        return $property;
    }

    public function find(string $name): ?ClaimInterface
    {
        $property = $this->findProperty($name);
        if (null !== $property && !$property instanceof ClaimInterface) {
            throw new UnexpectedValueException('Expects a claim.');
        }

        return $property;
    }

    public function has(string $name): bool
    {
        return $this->hasProperty($name);
    }

    public function set(ClaimInterface $property): void
    {
        $this->setProperty($property);
    }
}
