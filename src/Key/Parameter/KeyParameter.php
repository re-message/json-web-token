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

namespace RM\Standard\Jwt\Key\Parameter;

use Override;

/**
 * @template T of mixed
 *
 * @template-implements KeyParameterInterface<T>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class KeyParameter implements KeyParameterInterface
{
    /**
     * @param T $value
     */
    public function __construct(
        private readonly string $name,
        private readonly mixed $value = null
    ) {}

    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    #[Override]
    public function getValue(): mixed
    {
        return $this->value;
    }
}
