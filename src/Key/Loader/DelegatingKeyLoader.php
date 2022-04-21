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

namespace RM\Standard\Jwt\Key\Loader;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RM\Standard\Jwt\Exception\LoaderNotSupportResource;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class DelegatingKeyLoader implements KeyLoaderInterface
{
    /**
     * @var Collection<int, KeyLoaderInterface>
     */
    private readonly Collection $loaders;

    /**
     * @param iterable<KeyLoaderInterface> $loaders
     */
    public function __construct(iterable $loaders = [])
    {
        $this->loaders = new ArrayCollection();

        foreach ($loaders as $loader) {
            $this->pushLoader($loader);
        }
    }

    /**
     * @inheritDoc
     */
    public function load(ResourceInterface $resource): array
    {
        $loader = $this->findLoader($resource);
        if (null === $loader) {
            throw new LoaderNotSupportResource($this, $resource, __METHOD__);
        }

        return $loader->load($resource);
    }

    public function pushLoader(KeyLoaderInterface $loader): void
    {
        $this->loaders->add($loader);
    }

    /**
     * @inheritDoc
     */
    public function supports(ResourceInterface $resource): bool
    {
        return null !== $this->findLoader($resource);
    }

    protected function findLoader(ResourceInterface $resource): KeyLoaderInterface|null
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($resource)) {
                return $loader;
            }
        }

        return null;
    }
}
