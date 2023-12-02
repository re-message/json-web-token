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

namespace RM\Standard\Jwt\Key\Transformer\PublicKey;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Override;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class DelegatingPublicKeyTransformer implements PublicKeyTransformerInterface
{
    /**
     * @var Collection<int, PublicKeyTransformerInterface>
     */
    private readonly Collection $collection;

    /**
     * @param iterable<PublicKeyTransformerInterface> $transformers
     */
    public function __construct(iterable $transformers = [])
    {
        $this->collection = new ArrayCollection();

        foreach ($transformers as $transformer) {
            $this->pushTransformer($transformer);
        }
    }

    public function pushTransformer(PublicKeyTransformerInterface $transformer): void
    {
        $this->collection->add($transformer);
    }

    #[Override]
    public function transform(KeyInterface $privateKey): KeyInterface
    {
        $type = $privateKey->getType();
        $transformer = $this->findTransformer($type);
        if (null === $transformer) {
            $message = sprintf('The key with type "%s" not supported.', $type);

            throw new InvalidArgumentException($message);
        }

        return $transformer->transform($privateKey);
    }

    #[Override]
    public function supports(string $type): bool
    {
        return null !== $this->findTransformer($type);
    }

    protected function findTransformer(string $type): ?PublicKeyTransformerInterface
    {
        foreach ($this->collection as $transformer) {
            if ($transformer->supports($type)) {
                return $transformer;
            }
        }

        return null;
    }
}
