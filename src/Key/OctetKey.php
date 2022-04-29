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

namespace RM\Standard\Jwt\Key;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
final class OctetKey extends Key
{
    public function __construct(string $value, string $id = null)
    {
        $parameters = [
            self::PARAM_KEY_TYPE => self::KEY_TYPE_OCTET,
            self::PARAM_KEY_VALUE => $value,
        ];

        if (null !== $id) {
            $parameters[self::PARAM_KEY_IDENTIFIER] = $id;
        }

        parent::__construct($parameters);
    }

    public function getValue(): string
    {
        return $this->get(self::PARAM_KEY_VALUE);
    }
}
