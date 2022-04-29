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

namespace RM\Standard\Jwt\Key\Parameter\Factory;

use RM\Standard\Jwt\Key\Parameter\KeyParameterInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ParameterFactory implements ParameterFactoryInterface
{
    /**
     * @var array<string, class-string<KeyParameterInterface>>
     */
    private array $classMap;

    /**
     * @var class-string<KeyParameterInterface>
     */
    private string $defaultClass;

    /**
     * @param array<string, class-string<KeyParameterInterface>> $classMap
     * @param class-string<KeyParameterInterface>                $defaultClass
     */
    public function __construct(
        array $classMap,
        string $defaultClass
    ) {
        $this->classMap = $classMap;
        $this->defaultClass = $defaultClass;
    }

    /**
     * @inheritDoc
     */
    public function register(string $name, string $class): void
    {
        $this->classMap[$name] = $class;
    }

    public function create(string $name, mixed $value): KeyParameterInterface
    {
        $class = $this->classMap[$name] ?? null;
        if (null === $class) {
            return new $this->defaultClass($name, $value);
        }

        return new $class($value);
    }
}
