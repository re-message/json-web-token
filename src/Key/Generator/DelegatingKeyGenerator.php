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

namespace RM\Standard\Jwt\Key\Generator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class DelegatingKeyGenerator implements KeyGeneratorInterface
{
    /**
     * @var Collection<int, KeyGeneratorInterface>
     */
    private readonly Collection $collection;

    /**
     * @param iterable<KeyGeneratorInterface> $generators
     */
    public function __construct(iterable $generators = [])
    {
        $this->collection = new ArrayCollection();

        foreach ($generators as $generator) {
            $this->pushGenerator($generator);
        }
    }

    public function pushGenerator(KeyGeneratorInterface $generator): void
    {
        $this->collection->add($generator);
    }

    public function generate(string $type, array $options = []): KeyInterface
    {
        $generator = $this->findGenerator($type);
        if (null === $generator) {
            $message = sprintf('The key with type "%s" not supported.', $type);

            throw new InvalidArgumentException($message);
        }

        return $generator->generate($type, $options);
    }

    public function supports(string $type): bool
    {
        return null !== $this->findGenerator($type);
    }

    protected function findGenerator(string $type): ?KeyGeneratorInterface
    {
        foreach ($this->collection as $transformer) {
            if ($transformer->supports($type)) {
                return $transformer;
            }
        }

        return null;
    }
}
