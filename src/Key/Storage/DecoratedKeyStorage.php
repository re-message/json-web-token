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

namespace RM\Standard\Jwt\Key\Storage;

use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
abstract class DecoratedKeyStorage implements KeyStorageInterface
{
    public function __construct(
        private readonly KeyStorageInterface $storage
    ) {
    }

    /**
     * @inheritDoc
     */
    public function get(int|string $id): KeyInterface
    {
        return $this->storage->get($id);
    }

    /**
     * @inheritDoc
     */
    public function find(int|string $id): KeyInterface|null
    {
        return $this->storage->find($id);
    }

    /**
     * @inheritDoc
     */
    public function add(KeyInterface $key): void
    {
        $this->storage->add($key);
    }

    /**
     * @inheritDoc
     */
    public function addAll(iterable $keys): void
    {
        $this->storage->addAll($keys);
    }

    /**
     * @inheritDoc
     */
    public function has(int|string $id): bool
    {
        return $this->storage->has($id);
    }
}
