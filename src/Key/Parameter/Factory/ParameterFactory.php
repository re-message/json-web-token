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

use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Key\Parameter\KeyParameter;
use RM\Standard\Jwt\Key\Parameter\KeyParameterInterface;
use RM\Standard\Jwt\Key\Parameter\KeyUse;
use RM\Standard\Jwt\Key\Parameter\Operations;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ParameterFactory implements ParameterFactoryInterface
{
    public const DEFAULT_CLASS_MAP = [
        Type::NAME => Type::class,
        Value::NAME => Value::class,
        Identifier::NAME => Identifier::class,
        KeyUse::NAME => KeyUse::class,
        Operations::NAME => Operations::class,
    ];

    public const DEFAULT_CLASS = KeyParameter::class;

    /**
     * @var array<string, class-string<KeyParameterInterface>>
     */
    private array $classMap;

    /**
     * @var class-string<KeyParameterInterface>
     */
    private readonly string $defaultClass;

    /**
     * @param array<string, class-string<KeyParameterInterface>> $classMap
     */
    public function __construct(array $classMap = [])
    {
        $this->classMap = array_merge(self::DEFAULT_CLASS_MAP, $classMap);
        $this->defaultClass = self::DEFAULT_CLASS;
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
