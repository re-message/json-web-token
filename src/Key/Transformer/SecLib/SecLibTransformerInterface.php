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

namespace RM\Standard\Jwt\Key\Transformer\SecLib;

use phpseclib3\Crypt\Common\AsymmetricKey;
use RM\Standard\Jwt\Exception\InvalidKeyException;
use RM\Standard\Jwt\Key\KeyInterface;

/**
 * Transformer between JWK and phpseclib keys to use their algorithm implementations.
 *
 * @template T of AsymmetricKey
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
interface SecLibTransformerInterface
{
    /**
     * @param class-string<T> $type
     *
     * @throws InvalidKeyException
     *
     * @return T
     */
    public function transform(KeyInterface $key, string $type): AsymmetricKey;

    /**
     * @param T $key
     *
     * @throws InvalidKeyException
     */
    public function reverseTransform(AsymmetricKey $key): KeyInterface;

    /**
     * @psalm-assert-if-true class-string<T> $type
     */
    public function supports(string $type): bool;
}
