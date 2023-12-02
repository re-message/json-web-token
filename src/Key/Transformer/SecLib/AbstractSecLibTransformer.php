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

use InvalidArgumentException;
use ParagonIE\ConstantTime\Base64UrlSafe;
use phpseclib3\Crypt\Common\AsymmetricKey;
use phpseclib3\Crypt\Common\PublicKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Math\BigInteger;
use RM\Standard\Jwt\Exception\InvalidKeyException;
use RM\Standard\Jwt\Key\Factory\KeyFactoryInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Transformer\PublicKey\PublicKeyTransformerInterface;

/**
 * @template T of AsymmetricKey
 * @template-implements SecLibTransformerInterface<T>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract class AbstractSecLibTransformer implements SecLibTransformerInterface
{
    public function __construct(
        private readonly KeyFactoryInterface $factory,
        private readonly PublicKeyTransformerInterface $publicKeyTransformer,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function transform(KeyInterface $key, string $type): AsymmetricKey
    {
        if (!$this->supports($type)) {
            $message = sprintf(
                '%s does not support the key %s.',
                static::class,
                $type,
            );

            throw new InvalidArgumentException($message);
        }

        $expectPublic = is_a($type, PublicKey::class, true);
        $canTransform = $this->publicKeyTransformer->supports($key->getType());
        if ($expectPublic && $canTransform) {
            $key = $this->publicKeyTransformer->transform($key);
        }

        $components = $this->toComponents($key->all());
        $target = PublicKeyLoader::load($components);
        if (is_a($target, $type, false)) {
            return $target;
        }

        $message = sprintf(
            'Returned key must implement %s, given %s.',
            $type,
            $target::class
        );

        throw new InvalidKeyException($message);
    }

    abstract protected function toComponents(array $parameters): array;

    protected function toComponent(string $value): BigInteger
    {
        $bytes = Base64UrlSafe::decode($value);

        return new BigInteger($bytes, 256);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(AsymmetricKey $key): KeyInterface
    {
        if (!$this->supports($key::class)) {
            $message = sprintf(
                '%s does not support the key %s.',
                static::class,
                $key::class,
            );

            throw new InvalidArgumentException($message);
        }

        /** @var array $components */
        $components = $key->toString('raw');
        $content = $this->fromComponents($components);

        if (!$this->factory->supports($content)) {
            throw new InvalidKeyException('Unable to create key from phpseclib.');
        }

        return $this->factory->create($content);
    }

    abstract protected function fromComponents(array $components): array;

    protected function fromComponent(BigInteger $value): string
    {
        $bytes = $value->toBytes();

        return Base64UrlSafe::encodeUnpadded($bytes);
    }
}
