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

namespace RM\Standard\Jwt\Key\Factory;

use RM\Standard\Jwt\Exception\UnsupportedKeyException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\OctetKey;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class OctetKeyFactory implements KeyFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $content): KeyInterface
    {
        $type = $content[KeyInterface::PARAM_KEY_TYPE];
        if (KeyInterface::KEY_TYPE_OCTET !== $type) {
            throw new UnsupportedKeyException($type);
        }

        $id = $content[KeyInterface::PARAM_KEY_IDENTIFIER] ?? null;
        $value = $content[KeyInterface::PARAM_KEY_VALUE];

        return new OctetKey($value, $id);
    }
}
