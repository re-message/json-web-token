<?php
/*
 * This file is a part of Re Message Json Web Token implementation.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/json-web-token
 * @link      https://dev.remessage.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Re Message
 * @author    Oleg Kozlov <h1karo@remessage.ru>
 * @license   Apache License 2.0
 * @license   https://legal.remessage.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Key\Loader;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use RM\Standard\Jwt\Exception\LoaderException;
use RM\Standard\Jwt\Exception\NotSupportedResourceException;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;
use RM\Standard\Jwt\Key\Resource\Url;
use RM\Standard\Jwt\Key\Set\KeySetSerializerInterface;

/**
 * @template-implements KeyLoaderInterface<Url>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
            throw new NotSupportedResourceException($this::class, $resource::class, __METHOD__);
        }

        $request = $this->requestFactory->createRequest('GET', $resource->getAddress());
        foreach ($resource->getHeaders() as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        $response = $this->client->sendRequest($request);
        if (!$this->validateResponse($response)) {
            if (!$resource->isRequired()) {
                return [];
            }

            throw new LoaderException('Unable to get the key set.');
        }

        $content = $response->getBody()->getContents();

        return $this->serializer->deserialize($content);
    }

    protected function validateResponse(ResponseInterface $response): bool
    {
        return $response->getStatusCode() < 400;
    }

    public function supports(ResourceInterface $resource): bool
    {
        return $resource instanceof Url;
    }
}
