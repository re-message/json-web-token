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

namespace RM\Standard\Jwt\Key\Transformer\SecLib;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Override;
use phpseclib3\Crypt\Common\AsymmetricKey;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @template T of AsymmetricKey
 *
 * @template-implements SecLibTransformerInterface<T>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
readonly class DelegatingSecLibTransformer implements SecLibTransformerInterface
{
    /**
     * @var Collection<int, SecLibTransformerInterface<T>>
     */
    private Collection $collection;

    /**
     * @param iterable<SecLibTransformerInterface<T>> $transformers
     */
    public function __construct(iterable $transformers = [])
    {
        $this->collection = new ArrayCollection();

        foreach ($transformers as $transformer) {
            $this->pushTransformer($transformer);
        }
    }

    /**
     * @param SecLibTransformerInterface<T> $transformer
     */
    public function pushTransformer(SecLibTransformerInterface $transformer): void
    {
        $this->collection->add($transformer);
    }

    #[Override]
    public function transform(KeyInterface $key, string $type): AsymmetricKey
    {
        $transformer = $this->findTransformer($type);
        if (null === $transformer) {
            $message = sprintf('The key %s not supported.', $type);

            throw new InvalidArgumentException($message);
        }

        return $transformer->transform($key, $type);
    }

    #[Override]
    public function reverseTransform(AsymmetricKey $key): KeyInterface
    {
        $transformer = $this->findTransformer($key::class);
        if (null === $transformer) {
            $message = sprintf('The key %s not supported.', $key::class);

            throw new InvalidArgumentException($message);
        }

        return $transformer->reverseTransform($key);
    }

    #[Override]
    public function supports(string $type): bool
    {
        return null !== $this->findTransformer($type);
    }

    /**
     * @return SecLibTransformerInterface<T>|null
     */
    protected function findTransformer(string $type): ?SecLibTransformerInterface
    {
        foreach ($this->collection as $transformer) {
            if ($transformer->supports($type)) {
                return $transformer;
            }
        }

        return null;
    }
}
