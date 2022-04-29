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

namespace RM\Standard\Jwt\Algorithm\Signature\HMAC;

use InvalidArgumentException;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract class HMAC implements SignatureAlgorithmInterface
{
    final public function allowedKeyTypes(): array
    {
        return [KeyInterface::TYPE_OCTET];
    }

    final public function hash(KeyInterface $key, string $input): string
    {
        $k = $this->getKey($key);
        $algorithm = $this->getHashAlgorithm();

        return hash_hmac($algorithm, $input, $k, true);
    }

    final public function verify(KeyInterface $key, string $input, string $hash): bool
    {
        $expected = $this->hash($key, $input);

        return hash_equals($expected, $hash);
    }

    protected function getKey(KeyInterface $key): string
    {
        if (!in_array($key->getType(), $this->allowedKeyTypes(), true)) {
            throw new InvalidArgumentException('Wrong key type.');
        }

        if (!$key->has(KeyInterface::PARAM_VALUE)) {
            $message = sprintf('The key parameter "%s" is missing.', KeyInterface::PARAM_VALUE);

            throw new InvalidArgumentException($message);
        }

        $k = $key->get(KeyInterface::PARAM_VALUE);

        if (mb_strlen($k, '8bit') < 32) {
            throw new InvalidArgumentException('Invalid key length.');
        }

        return $k;
    }

    /**
     * Returns name of HMAC hash algorithm, like "sha256".
     */
    abstract protected function getHashAlgorithm(): string;
}
