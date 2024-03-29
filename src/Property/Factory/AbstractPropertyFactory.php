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

namespace RM\Standard\Jwt\Property\Factory;

use Override;
use RM\Standard\Jwt\Property\PropertyInterface;

/**
 * @template T of PropertyInterface
 *
 * @template-implements PropertyFactoryInterface<T>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract class AbstractPropertyFactory implements PropertyFactoryInterface
{
    /**
     * @param array<string, class-string<T>> $classMap
     * @param class-string<T>                $defaultPropertyClass
     */
    public function __construct(
        private array $classMap,
        private readonly string $defaultPropertyClass,
    ) {}

    /**
     * @param class-string<T> $class
     */
    #[Override]
    public function register(string $name, string $class): void
    {
        $this->classMap[$name] = $class;
    }

    #[Override]
    public function create(string $name, mixed $value): PropertyInterface
    {
        $class = $this->classMap[$name] ?? null;
        if (null === $class) {
            return new $this->defaultPropertyClass($name, $value);
        }

        return new $class($value);
    }
}
