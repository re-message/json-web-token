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

namespace RM\Standard\Jwt\Key\Thumbprint;

use InvalidArgumentException;
use ParagonIE\ConstantTime\Base64UrlSafe;
use RM\Standard\Jwt\Format\FormatterInterface;
use RM\Standard\Jwt\Format\JsonFormatter;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ThumbprintFactory implements ThumbprintFactoryInterface
{
    // @todo make use parameter constants on implement
    public const THUMBPRINT_PARAMETERS = [
        Type::NAME,
        Value::NAME,
        'e',
        'n',
        'crv',
        'x',
        'y',
    ];

    public function __construct(
        private readonly FormatterInterface $formatter = new JsonFormatter(),
    ) {
    }

    public function create(KeyInterface $key, string $hashAlgorithm): string
    {
        if (!in_array($hashAlgorithm, hash_algos(), true)) {
            $message = sprintf('The hash algorithm "%s" is not supported.', $hashAlgorithm);

            throw new InvalidArgumentException($message);
        }

        $values = array_intersect_key($key->all(), array_flip(self::THUMBPRINT_PARAMETERS));
        ksort($values);

        $input = $this->formatter->encode($values);
        $rawHash = hash($hashAlgorithm, $input, true);

        return Base64UrlSafe::encodeUnpadded($rawHash);
    }
}
