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

namespace RM\Standard\Jwt\Key\Factory;

use RM\Standard\Jwt\Exception\UnsupportedKeyException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\OctetKey;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
