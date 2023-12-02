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

namespace RM\Standard\Jwt\Key\Storage;

use Override;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract readonly class DecoratedKeyStorage implements KeyStorageInterface
{
    public function __construct(
        private KeyStorageInterface $storage
    ) {}

    #[Override]
    public function get(int|string $id): KeyInterface
    {
        return $this->storage->get($id);
    }

    #[Override]
    public function find(int|string $id): KeyInterface|null
    {
        return $this->storage->find($id);
    }

    #[Override]
    public function findBy(string $name, mixed $value): array
    {
        return $this->storage->findBy($name, $value);
    }

    #[Override]
    public function findByType(string $type): array
    {
        return $this->storage->findByType($type);
    }

    #[Override]
    public function has(int|string $id): bool
    {
        return $this->storage->has($id);
    }

    #[Override]
    public function add(KeyInterface $key): void
    {
        $this->storage->add($key);
    }

    #[Override]
    public function addAll(iterable $keys): void
    {
        $this->storage->addAll($keys);
    }

    #[Override]
    public function toArray(): array
    {
        return $this->storage->toArray();
    }
}
