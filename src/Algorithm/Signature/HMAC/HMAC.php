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

namespace RM\Standard\Jwt\Algorithm\Signature\HMAC;

use InvalidArgumentException;
use Override;
use ParagonIE\ConstantTime\Base64UrlSafe;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract readonly class HMAC implements SignatureAlgorithmInterface
{
    #[Override]
    final public function allowedKeyTypes(): array
    {
        return [Type::OCTET];
    }

    #[Override]
    final public function sign(KeyInterface $key, string $input): string
    {
        $k = $this->getKey($key);
        $algorithm = $this->getHashAlgorithm();
        if (!in_array($algorithm, hash_hmac_algos(), true)) {
            $message = sprintf('Your platform does not support the HMAC algorithm "%s".', $algorithm);

            throw new InvalidArgumentException($message);
        }

        return hash_hmac($algorithm, $input, $k, true);
    }

    #[Override]
    final public function verify(KeyInterface $key, string $input, string $signature): bool
    {
        $expected = $this->sign($key, $input);

        return hash_equals($expected, $signature);
    }

    protected function getKey(KeyInterface $key): string
    {
        if (!in_array($key->getType(), $this->allowedKeyTypes(), true)) {
            throw new InvalidArgumentException('Wrong key type.');
        }

        if (!$key->has(Value::NAME)) {
            $message = sprintf('The key parameter "%s" is missing.', Value::NAME);

            throw new InvalidArgumentException($message);
        }

        $k = $key->get(Value::NAME)->getValue();

        if (mb_strlen($k, '8bit') < 32) {
            throw new InvalidArgumentException('Invalid key length.');
        }

        return Base64UrlSafe::decode($k);
    }

    /**
     * Returns name of HMAC hash algorithm, like "sha256".
     */
    abstract protected function getHashAlgorithm(): string;
}
