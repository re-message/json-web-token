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
use RM\Standard\Jwt\Key\Resource\ResourceInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class ResourceLoader implements ResourceLoaderInterface
{
    /**
     * @var Collection<int, ResourceInterface>
     */
    private readonly Collection $resources;

    /**
     * @param iterable<ResourceInterface> $resources
     */
    public function __construct(
        private readonly KeyLoaderInterface $loader,
        iterable $resources = [],
    ) {
        $this->resources = new ArrayCollection();

        foreach ($resources as $resource) {
            $this->pushResource($resource);
        }
    }

    /**
     * @inheritDoc
     */
    public function load(): iterable
    {
        foreach ($this->resources as $resource) {
            if ($this->loader->supports($resource)) {
                yield from $this->loader->load($resource);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function pushResource(ResourceInterface $resource): void
    {
        $this->resources->add($resource);
    }
}
