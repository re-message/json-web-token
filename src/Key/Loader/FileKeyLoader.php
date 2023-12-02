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

use RM\Standard\Jwt\Exception\LoaderException;
use RM\Standard\Jwt\Exception\NotSupportedResourceException;
use RM\Standard\Jwt\Key\Resource\File;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;
use RM\Standard\Jwt\Key\Set\KeySetSerializerInterface;

/**
 * @template-implements KeyLoaderInterface<File>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class FileKeyLoader implements KeyLoaderInterface
{
    public function __construct(
        private readonly KeySetSerializerInterface $serializer,
    ) {}

    /**
     * @inheritDoc
     */
    public function load(ResourceInterface $resource): array
    {
        if (!$resource instanceof File) {
            throw new NotSupportedResourceException($this::class, $resource::class, __METHOD__);
        }

        $path = $resource->getPath();
        if (!file_exists($path)) {
            if (!$resource->isRequired()) {
                return [];
            }

            throw new LoaderException(sprintf('File "%s" does not exist.', $path));
        }

        if (!is_file($path) || !is_readable($path)) {
            throw new LoaderException(sprintf('File "%s" cannot be read.', $path));
        }

        $content = file_get_contents($path);
        if (false === $content) {
            throw new LoaderException(sprintf('Unable get content from file "%s".', $path));
        }

        return $this->serializer->deserialize($content);
    }

    /**
     * @inheritDoc
     */
    public function supports(ResourceInterface $resource): bool
    {
        return $resource instanceof File;
    }
}
