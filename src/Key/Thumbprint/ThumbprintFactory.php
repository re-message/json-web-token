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

namespace RM\Standard\Jwt\Key\Thumbprint;

use InvalidArgumentException;
use ParagonIE\ConstantTime\Base64UrlSafe;
use RM\Standard\Jwt\Format\FormatterInterface;
use RM\Standard\Jwt\Format\JsonFormatter;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ThumbprintFactory implements ThumbprintFactoryInterface
{
    public function __construct(
        private readonly string $algorithm = self::DEFAULT_ALGORITHM,
        private readonly FormatterInterface $formatter = new JsonFormatter(),
    ) {
        if (!in_array($algorithm, hash_algos(), true)) {
            $message = sprintf('The hash algorithm "%s" is not supported.', $algorithm);

            throw new InvalidArgumentException($message);
        }
    }

    public function create(KeyInterface $key): string
    {
        $values = array_intersect_key($key->all(), array_flip(self::THUMBPRINT_PARAMETERS));
        ksort($values);

        $input = $this->formatter->encode($values);
        $rawHash = hash($this->algorithm, $input, true);

        return Base64UrlSafe::encodeUnpadded($rawHash);
    }
}
