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

namespace RM\Standard\Jwt\Key\Factory;

use RM\Standard\Jwt\Key\Parameter\Factory\ParameterFactory;
use RM\Standard\Jwt\Key\Parameter\Factory\ParameterFactoryInterface;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class OctetKeyFactory extends AbstractKeyFactory
{
    public const DEFAULT_CLASS_MAP = [
        Value::NAME => Value::class,
    ];

    public function __construct(
        ParameterFactoryInterface $parameterFactory = new ParameterFactory(self::DEFAULT_CLASS_MAP),
    ) {
        parent::__construct(
            [Type::OCTET],
            [Value::NAME],
            $parameterFactory,
        );
    }

    public static function fromMap(array $classMap = []): self
    {
        $classMap = array_merge(self::DEFAULT_CLASS_MAP, $classMap);
        $parameterFactory = new ParameterFactory($classMap);

        return new self($parameterFactory);
    }
}
