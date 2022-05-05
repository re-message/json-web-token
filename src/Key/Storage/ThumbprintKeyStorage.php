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

namespace RM\Standard\Jwt\Key\Storage;

use RM\Standard\Jwt\Key\Key;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactory;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactoryInterface;
use RM\Standard\Jwt\Property\Header\KeyId;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ThumbprintKeyStorage extends DecoratedKeyStorage
{
    public const DEFAULT_ALGORITHM = 'sha256';

    public function __construct(
        KeyStorageInterface $storage,
        private readonly ThumbprintFactoryInterface $thumbprintFactory = new ThumbprintFactory(),
        private readonly string $algorithm = self::DEFAULT_ALGORITHM,
    ) {
        parent::__construct($storage);
    }

    public function add(KeyInterface $key): void
    {
        if (!$key->has(Identifier::NAME)) {
            $thumbprint = $this->thumbprintFactory->create($key, $this->algorithm);
            $parameters = array_merge($key->getParameters(), [new KeyId($thumbprint)]);
            $key = new Key($parameters);
        }

        parent::add($key);
    }

    public function addAll(iterable $keys): void
    {
        foreach ($keys as $key) {
            $this->add($key);
        }
    }
}
