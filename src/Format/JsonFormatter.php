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

namespace RM\Standard\Jwt\Format;

use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class JsonFormatter implements FormatterInterface
{
    private JsonEncoder $encoder;

    public function __construct()
    {
        $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        $encode = new JsonEncode(
            [
                JsonEncode::OPTIONS => JSON_FORCE_OBJECT | $options,
            ]
        );
        $decode = new JsonDecode(
            [
                JsonDecode::ASSOCIATIVE => true,
                JsonDecode::OPTIONS => $options,
            ]
        );

        $this->encoder = new JsonEncoder($encode, $decode);
    }

    public function encode(array $data): string
    {
        return $this->encoder->encode($data, 'json');
    }

    public function decode(string $data): array
    {
        return $this->encoder->decode($data, 'json');
    }
}
