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

namespace RM\Standard\Jwt\Key\Set;

use BadMethodCallException;
use RM\Standard\Jwt\Exception\InvalidKeyException;
use RM\Standard\Jwt\Format\FormatterInterface;
use RM\Standard\Jwt\Key\Factory\KeyFactoryInterface;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class KeySetSerializer implements KeySetSerializerInterface
{
    public function __construct(
        private readonly KeyFactoryInterface $factory,
        private readonly FormatterInterface $formatter,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function serialize(array $set): string
    {
        throw new BadMethodCallException('Not implemented');
    }

    /**
     * @inheritDoc
     */
    public function deserialize(string $serialized): array
    {
        $array = $this->formatter->decode($serialized);

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

    protected function create(array $content): KeyInterface|null
    {
        try {
            return $this->factory->create($content);
        } catch (InvalidKeyException) {
            return null;
        }
    }
}