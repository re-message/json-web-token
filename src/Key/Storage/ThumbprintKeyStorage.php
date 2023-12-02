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

namespace RM\Standard\Jwt\Key\Storage;

use Override;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactory;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactoryInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ThumbprintKeyStorage extends DecoratedKeyStorage
{
    public function __construct(
        KeyStorageInterface $storage,
        private readonly ThumbprintFactoryInterface $thumbprintFactory = new ThumbprintFactory(),
    ) {
        parent::__construct($storage);
    }

    #[Override]
    public function add(KeyInterface $key): void
    {
        if (!$key->has(Identifier::NAME)) {
            $thumbprint = $this->thumbprintFactory->create($key);
            $key->set(new Identifier($thumbprint));
        }

        parent::add($key);
    }

    #[Override]
    public function addAll(iterable $keys): void
    {
        foreach ($keys as $key) {
            $this->add($key);
        }
    }
}
