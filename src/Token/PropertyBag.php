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
use Doctrine\Common\Collections\Collection;
use RM\Standard\Jwt\Exception\PropertyNotFoundException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @template T of PropertyInterface
 */
abstract class PropertyBag
{
    /**
     * @var Collection<string, PropertyInterface>
     */
    private Collection $collection;

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
     *
     * @return T
     */
    public function get(string $name): PropertyInterface
    {
        return $this->find($name) ?: throw new PropertyNotFoundException($name);
    }

    /**
     * @return T|null
     */
    public function find(string $name): ?PropertyInterface
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

    public function has(string $name): bool
    {
        return null !== $this->find($name);
    }

    /**
     * @param T $property
     */
    public function set(PropertyInterface $property): void
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
