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

use BadMethodCallException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Loader\ResourceLoaderInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class LoadableKeyStorage extends DecoratedKeyStorage
{
    private bool $loaded = false;

    public function __construct(
        KeyStorageInterface $storage,
        private readonly ResourceLoaderInterface $resourceLoader,
        bool $lazy = true,
    ) {
        parent::__construct($storage);

        if (!$lazy) {
            $this->load();
        }
    }

    /**
     * @inheritDoc
     */
    public function get(int|string $id): KeyInterface
    {
        if (!$this->loaded) {
            $this->load();
        }

        return parent::get($id);
    }

    /**
     * @inheritDoc
     */
    public function find(int|string $id): KeyInterface|null
    {
        if (!$this->loaded) {
            $this->load();
        }

        return parent::find($id);
    }

    /**
     * @inheritDoc
     */
    public function has(int|string $id): bool
    {
        if (!$this->loaded) {
            $this->load();
        }

        return parent::has($id);
    }

    protected function load(): void
    {
        if ($this->loaded) {
            throw new BadMethodCallException('Keys already loaded. Check before call.');
        }

        $keys = $this->resourceLoader->load();
        $this->addAll($keys);

        $this->loaded = true;
    }
}
