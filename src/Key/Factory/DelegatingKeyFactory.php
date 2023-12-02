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

namespace RM\Standard\Jwt\Key\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Override;
use RM\Standard\Jwt\Exception\UnsupportedKeyException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Type;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class DelegatingKeyFactory implements KeyFactoryInterface
{
    /**
     * @var Collection<int, KeyFactoryInterface>
     */
    private readonly Collection $collection;

    /**
     * @param iterable<KeyFactoryInterface> $loaders
     */
    public function __construct(iterable $loaders = [])
    {
        $this->collection = new ArrayCollection();

        foreach ($loaders as $loader) {
            $this->pushFactory($loader);
        }
    }

    public function pushFactory(KeyFactoryInterface $loader): void
    {
        $this->collection->add($loader);
    }

    #[Override]
    public function create(array $content): KeyInterface
    {
        $factory = $this->findFactory($content);
        if (null === $factory) {
            $type = $content[Type::NAME] ?? null;

            throw new UnsupportedKeyException($type, static::class);
        }

        return $factory->create($content);
    }

    #[Override]
    public function supports(array $content): bool
    {
        return null !== $this->findFactory($content);
    }

    protected function findFactory(array $content): KeyFactoryInterface|null
    {
        foreach ($this->collection as $factory) {
            if ($factory->supports($content)) {
                return $factory;
            }
        }

        return null;
    }
}
