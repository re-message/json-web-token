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

namespace RM\Standard\Jwt\Key\Storage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RM\Standard\Jwt\Exception\KeyNotFoundException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Key\Parameter\Type;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class RuntimeKeyStorage implements KeyStorageInterface
{
    /**
     * @var Collection<int|string, KeyInterface>
     */
    private readonly Collection $keys;

    public function __construct()
    {
        $this->keys = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function get(int|string $id): KeyInterface
    {
        return $this->find($id) ?? throw new KeyNotFoundException($id);
    }

    /**
     * @inheritDoc
     */
    public function find(int|string $id): KeyInterface|null
    {
        return $this->keys->get($id);
    }

    /**
     * @inheritDoc
     */
    public function findBy(string $name, mixed $value): iterable
    {
        $filter = fn (KeyInterface $key): bool => $value === $key->find($name)?->getValue();

        return $this->keys->filter($filter);
    }

    /**
     * @inheritDoc
     */
    public function findByType(string $type): iterable
    {
        return $this->findBy(Type::NAME, $type);
    }

    /**
     * @inheritDoc
     */
    public function has(int|string $id): bool
    {
        return $this->keys->containsKey($id);
    }

    /**
     * @inheritDoc
     */
    public function add(KeyInterface $key): void
    {
        $idParameter = $key->get(Identifier::NAME);
        $id = $idParameter->getValue();

        $this->keys->set($id, $key);
    }

    /**
     * @inheritDoc
     */
    public function addAll(iterable $keys): void
    {
        foreach ($keys as $key) {
            $this->add($key);
        }
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->keys->toArray();
    }
}
