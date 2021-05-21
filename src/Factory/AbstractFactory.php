<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2021 Relations Messenger
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
 * Class AbstractFactory
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @var array<string, class-string<PropertyInterface>>
     */
    private array $classMap;

    private string $defaultPropertyClass;

    public function __construct(array $classMap, string $defaultPropertyClass)
    {
        $this->classMap = $classMap;
        $this->defaultPropertyClass = $defaultPropertyClass;
    }

    public function add(string $name, string $class): void
    {
        $this->classMap[$name] = $class;
    }

    public function create(string $name, mixed $value): PropertyInterface
    {
        $class = $this->classMap[$name] ?? null;
        if (null === $class) {
            return new $this->defaultPropertyClass($name, $value);
        }

        return new $class($value);
    }
}
