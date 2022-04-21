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

use RM\Standard\Jwt\Exception\LoaderException;
use RM\Standard\Jwt\Exception\NotSupportedResourceException;
use RM\Standard\Jwt\Key\Resource\File;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;
use RM\Standard\Jwt\Key\Set\KeySetSerializerInterface;

/**
 * @template-implements KeyLoaderInterface<File>
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class FileKeyLoader implements KeyLoaderInterface
{
    public function __construct(
        private readonly KeySetSerializerInterface $serializer,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function load(ResourceInterface $resource): array
    {
        if (!$resource instanceof File) {
            throw new NotSupportedResourceException($this::class, $resource::class, __METHOD__);
        }

        if (!is_file($resource->path)) {
            throw new LoaderException(sprintf('File "%s" does not exist.', $resource->path));
        }

        if (!is_readable($resource->path)) {
            throw new LoaderException(sprintf('File "%s" cannot be read.', $resource->path));
        }

        $content = file_get_contents($resource->path);
        if (false === $content) {
            throw new LoaderException(sprintf('Unable get content from file "%s".', $resource->path));
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
