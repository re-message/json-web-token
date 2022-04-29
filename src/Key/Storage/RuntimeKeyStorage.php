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
        $key = $this->find($id);
        if (null === $key) {
            throw new KeyNotFoundException($id);
        }

        return $key;
    }

    /**
     * @inheritDoc
     */
    public function find(int|string $id): KeyInterface|null
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
        $id = $key->get(KeyInterface::PARAM_IDENTIFIER);
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
    public function has(int|string $id): bool
    {
        return $this->keys->containsKey($id);
    }
}
