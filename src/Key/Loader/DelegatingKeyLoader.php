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
use RM\Standard\Jwt\Exception\NotSupportedResourceException;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;

/**
 * @template-implements KeyLoaderInterface<ResourceInterface>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
readonly class DelegatingKeyLoader implements KeyLoaderInterface
{
    /**
     * @var Collection<int, KeyLoaderInterface>
     */
    private Collection $loaders;

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

    #[Override]
    public function load(ResourceInterface $resource): array
    {
        $loader = $this->findLoader($resource);
        if (null === $loader) {
            throw new NotSupportedResourceException(static::class, $resource::class, __METHOD__);
        }

        return $loader->load($resource);
    }

    public function pushLoader(KeyLoaderInterface $loader): void
    {
        $this->loaders->add($loader);
    }

    #[Override]
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
