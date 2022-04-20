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
     * @var Collection<string|int, KeyInterface>
     */
    private readonly Collection $keys;

    public function __construct()
    {
        $this->keys = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function get(string|int $id): KeyInterface
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
    public function find(string|int $id): KeyInterface|null
    {
        $key = $this->keys->get($id);
        if (null === $key) {
            return null;
        }

        return $key;
    }

    /**
     * @inheritDoc
     */
    public function add(KeyInterface $key): void
    {
        $id = $key->get(KeyInterface::PARAM_KEY_IDENTIFIER);
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
    public function has(string|int $id): bool
    {
        return $this->keys->containsKey($id);
    }
}
