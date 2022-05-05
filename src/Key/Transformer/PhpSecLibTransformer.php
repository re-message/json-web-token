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

namespace RM\Standard\Jwt\Key\Transformer;

use phpseclib3\Crypt\Common\AsymmetricKey;
use phpseclib3\Crypt\PublicKeyLoader;
use RM\Standard\Jwt\Exception\InvalidKeyException;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class PhpSecLibTransformer
{
    /**
     * @template T of AsymmetricKey
     *
     * @param class-string<T> $type
     *
     * @throws InvalidKeyException
     *
     * @return T
     */
    public function transform(KeyInterface $key, string $type): AsymmetricKey
    {
        $target = PublicKeyLoader::load($key->all());
        if (is_a($target, $type, false)) {
            return $target;
        }

        $message = sprintf(
            'Returned key must implement %s, given %s.',
            $type,
            $target::class
        );

        throw new InvalidKeyException($message);
    }
}
