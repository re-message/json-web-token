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

use RM\Standard\Jwt\Exception\LoaderNotSupportResource;
use RM\Standard\Jwt\Format\FormatterInterface;
use RM\Standard\Jwt\Http\HttpClientInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\OctetKey;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;
use RM\Standard\Jwt\Key\Resource\Url;

/**
 * @template-implements KeyLoaderInterface<Url>
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class UrlKeyLoader implements KeyLoaderInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly FormatterInterface $formatter,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function load(ResourceInterface $resource): array
    {
        if (!$resource instanceof Url) {
            throw new LoaderNotSupportResource($this, $resource, __METHOD__);
        }

        $content = $this->client->getContent($resource->address, $resource->headers);
        $array = $this->formatter->decode($content);

        if (!array_key_exists(self::PARAM_KEYS, $array)) {
            return [];
        }

        $keys = [];
        foreach ($array[self::PARAM_KEYS] as $content) {
            if (!is_array($content)) {
                continue;
            }

            $key = $this->create($content);
            if (null === $key) {
                continue;
            }

            $keys[] = $key;
        }

        return $keys;
    }

    public function supports(ResourceInterface $resource): bool
    {
        return $resource instanceof Url;
    }

    /**
     * @todo key factory
     */
    protected function create(array $key): KeyInterface|null
    {
        $type = $key[KeyInterface::PARAM_KEY_TYPE];
        if ($type !== KeyInterface::KEY_TYPE_OCTET) {
            return null;
        }

        $id = $key[KeyInterface::PARAM_KEY_IDENTIFIER] ?? null;
        $value = $key[KeyInterface::PARAM_KEY_VALUE];

        return new OctetKey($value, $id);
    }
}
