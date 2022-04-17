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

namespace RM\Standard\Jwt\Factory;

use RM\Standard\Jwt\Token\PropertyInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @template T of PropertyInterface
 */
interface FactoryInterface
{
    /**
     * @return T
     */
    public function create(string $name, mixed $value): PropertyInterface;

    /**
     * @param class-string<T> $class
     */
    public function register(string $name, string $class): void;
}
