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

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use RM\Standard\Jwt\Exception\LoaderException;
use RM\Standard\Jwt\Exception\LoaderNotSupportResource;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;
use RM\Standard\Jwt\Key\Resource\Url;
use RM\Standard\Jwt\Key\Set\KeySetSerializerInterface;

/**
 * @template-implements KeyLoaderInterface<Url>
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class UrlKeyLoader implements KeyLoaderInterface
{
    public function __construct(
        private readonly KeySetSerializerInterface $serializer,
        private readonly ClientInterface $client,
        private readonly RequestFactoryInterface $requestFactory,
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

        $request = $this->requestFactory->createRequest('GET', $resource->address);
        foreach ($resource->headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() >= 400) {
            throw new LoaderException('Unable to get the key set.');
        }

        $content = $response->getBody()->getContents();

        return $this->serializer->deserialize($content);
    }

    public function supports(ResourceInterface $resource): bool
    {
        return $resource instanceof Url;
    }
}
