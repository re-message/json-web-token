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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RM\Standard\Jwt\Exception\KeyNotFoundException;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class RuntimeKeyStorage implements KeyStorageInterface
{
    /**
     * @var Collection<int, KeyInterface>
     */
    private readonly Collection $keys;

    public function __construct()
    {
        $this->keys = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): KeyInterface
    {
        $key = $this->find($id);
        if (null === $key) {
            throw new KeyNotFoundException($id);
        }

        return $key;
    }

    /**
     * @inheritDoc
     */
    public function find(string $id): KeyInterface|null
    {
        if (!$this->has($id)) {
            return null;
        }

        return $this->keys->get($id);
    }

    /**
     * @inheritDoc
     */
    public function add(KeyInterface $key): void
    {
        $this->keys->add($key);
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->keys->containsKey($id);
    }
}
