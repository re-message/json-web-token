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

namespace RM\Standard\Jwt\Key;

use InvalidArgumentException;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class Key implements KeyInterface
{
    private array $parameters;

    public function __construct(array $parameters)
    {
        $typeParameter = self::PARAM_TYPE;
        if (!array_key_exists($typeParameter, $parameters)) {
            $message = sprintf(
                'Any JSON Web Key must have the key type parameter (`%s`).',
                $typeParameter
            );

            throw new InvalidArgumentException($message);
        }

        $this->parameters = $parameters;
    }

    public function get(string $parameter): string
    {
        if (!$this->has($parameter)) {
            $message = sprintf(
                'The parameter with name `%s` is not exists in this key.',
                $parameter
            );

            throw new InvalidArgumentException($message);
        }

        return $this->parameters[$parameter];
    }

    public function has(string $parameter): bool
    {
        return array_key_exists($parameter, $this->parameters);
    }

    public function getType(): string
    {
        return $this->get(self::PARAM_TYPE);
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function jsonSerialize(): array
    {
        return $this->all();
    }
}
