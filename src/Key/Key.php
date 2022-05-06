<?php
/*
 * This file is a part of Re Message Json Web Token implementation.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/json-web-token
 * @link      https://dev.remessage.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Re Message
 * @author    Oleg Kozlov <h1karo@remessage.ru>
 * @license   Apache License 2.0
 * @license   https://legal.remessage.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Key;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use RM\Standard\Jwt\Key\Parameter\KeyParameterInterface;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactory;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactoryInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class Key implements KeyInterface
{
    private readonly Collection $collection;

    public function __construct(array $parameters)
    {
        $this->collection = new ArrayCollection();

        foreach ($parameters as $parameter) {
            $this->set($parameter);
        }

        if (!$this->has(Type::NAME)) {
            $message = sprintf(
                'Any JSON Web Key must have the key type parameter (`%s`).',
                Type::NAME
            );

            throw new InvalidArgumentException($message);
        }
    }

    public function get(string $name): KeyParameterInterface
    {
        if (!$this->collection->containsKey($name)) {
            $message = sprintf(
                'The parameter with name `%s` is not exists in this key.',
                $name
            );

            throw new InvalidArgumentException($message);
        }

        return $this->collection->get($name);
    }

    public function has(string $name): bool
    {
        return $this->collection->containsKey($name);
    }

    public function set(KeyParameterInterface $parameter): void
    {
        $this->collection->set($parameter->getName(), $parameter);
    }

    public function getType(): string
    {
        return $this->get(Type::NAME)->getValue();
    }

    public function toThumbprint(ThumbprintFactoryInterface $factory = null): string
    {
        $factory ??= new ThumbprintFactory();

        return $factory->create($this);
    }

    public function getParameters(): array
    {
        return $this->collection->toArray();
    }

    public function all(): array
    {
        /** @var Collection<string, mixed> $collection */
        $collection = new ArrayCollection();

        /** @var KeyParameterInterface $parameter */
        foreach ($this->collection as $parameter) {
            $collection->set($parameter->getName(), $parameter->getValue());
        }

        return $collection->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->all();
    }
}
