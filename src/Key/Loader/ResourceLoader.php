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

namespace RM\Standard\Jwt\Key\Loader;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Override;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
readonly class ResourceLoader implements ResourceLoaderInterface
{
    /**
     * @var Collection<int, ResourceInterface>
     */
    private Collection $resources;

    /**
     * @param iterable<ResourceInterface> $resources
     */
    public function __construct(
        private KeyLoaderInterface $loader,
        iterable $resources = [],
    ) {
        $this->resources = new ArrayCollection();

        foreach ($resources as $resource) {
            $this->pushResource($resource);
        }
    }

    #[Override]
    public function load(): iterable
    {
        foreach ($this->resources as $resource) {
            if ($this->loader->supports($resource)) {
                yield from $this->loader->load($resource);
            }
        }
    }

    #[Override]
    public function pushResource(ResourceInterface $resource): void
    {
        $this->resources->add($resource);
    }
}
