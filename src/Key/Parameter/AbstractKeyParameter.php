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

namespace RM\Standard\Jwt\Key\Parameter;

/**
 * @template T of mixed
 * @template-implements KeyParameterInterface<T>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract class AbstractKeyParameter implements KeyParameterInterface
{
    /**
     * @var T
     */
    private mixed $value;

    /**
     * @param T $value
     */
    public function __construct(mixed $value = null)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
