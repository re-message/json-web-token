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

use Doctrine\Common\Collections\ArrayCollection;
use RM\Standard\Jwt\Exception\PropertyNotFoundException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @method PropertyInterface get(string $name)
 * @method void              set(PropertyInterface $property)
 * @method bool              has(string $name)
 */
abstract class PropertyBag
{
    private ArrayCollection $collection;

    public function __construct(array $properties = [])
    {
        $this->collection = new ArrayCollection();

        foreach ($properties as $property) {
            $this->set($property);
        }
    }

    public function __clone(): void
    {
        $this->collection = clone $this->collection;
    }

    /**
     * @throws PropertyNotFoundException
     */
    protected function getProperty(string $name): PropertyInterface
    {
        return $this->findProperty($name) ?: throw new PropertyNotFoundException($name);
    }

    protected function findProperty(string $name): ?PropertyInterface
    {
        if ($this->collection->containsKey($name)) {
            return $this->collection->get($name);
        }

        /** @var PropertyInterface $property */
        foreach ($this->collection as $property) {
            if ($property->getName() === $name) {
                return $property;
            }
        }

        return null;
    }

    protected function hasProperty(string $name): bool
    {
        return null !== $this->findProperty($name);
    }

    protected function setProperty(PropertyInterface $property): void
    {
        $this->collection->set($property->getName(), $property);
    }

    public function toArray(): array
    {
        $collection = new ArrayCollection();

        /** @var PropertyInterface $property */
        foreach ($this->collection as $property) {
            $collection->set($property->getName(), $property->getValue());
        }

        return $collection->toArray();
    }
}
