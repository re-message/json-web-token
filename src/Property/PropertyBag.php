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

namespace RM\Standard\Jwt\Property;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Override;
use RM\Standard\Jwt\Exception\PropertyNotFoundException;

/**
 * @template T of PropertyInterface
 *
 * @template-implements PropertyBagInterface<T>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract readonly class PropertyBag implements PropertyBagInterface
{
    /**
     * @var Collection<string, T>
     */
    private Collection $collection;

    /**
     * @param array<int, T> $properties
     */
    public function __construct(array $properties = [])
    {
        $this->collection = new ArrayCollection();

        foreach ($properties as $property) {
            $this->set($property);
        }
    }

    public function __clone()
    {
        $properties = $this->getProperties();
        $this->collection = new ArrayCollection();

        foreach ($properties as $property) {
            $this->set(clone $property);
        }
    }

    #[Override]
    public function get(string $name): PropertyInterface
    {
        return $this->find($name) ?: throw new PropertyNotFoundException($name);
    }

    #[Override]
    public function find(string $name): ?PropertyInterface
    {
        if (!$this->collection->containsKey($name)) {
            return null;
        }

        return $this->collection->get($name);
    }

    #[Override]
    public function has(string $name): bool
    {
        return null !== $this->find($name);
    }

    #[Override]
    public function set(PropertyInterface $property): void
    {
        $this->collection->set($property->getName(), $property);
    }

    #[Override]
    public function getProperties(): array
    {
        return $this->collection->getValues();
    }

    #[Override]
    public function toArray(): array
    {
        /** @var Collection<string, mixed> $collection */
        $collection = new ArrayCollection();

        /** @var PropertyInterface $property */
        foreach ($this->collection as $property) {
            $collection->set($property->getName(), $property->getValue());
        }

        return $collection->toArray();
    }
}
