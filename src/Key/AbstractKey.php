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

namespace RM\Standard\Jwt\Key;

use InvalidArgumentException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
abstract class AbstractKey implements KeyInterface
{
    private array $parameters;

    public function __construct(array $parameters)
    {
        if (!array_key_exists(self::PARAM_KEY_TYPE, $parameters)) {
            throw new InvalidArgumentException(sprintf('Any JSON Web Key must have the key type parameter (`%s`).', self::PARAM_KEY_TYPE));
        }

        $this->parameters = $parameters;
    }

    public function get(string $parameter): string
    {
        if (!$this->has($parameter)) {
            throw new InvalidArgumentException(sprintf('The parameter with name `%s` is not exists in this key.', $parameter));
        }

        return $this->parameters[$parameter];
    }

    public function getType(): string
    {
        return $this->get(self::PARAM_KEY_TYPE);
    }

    public function has(string $parameter): bool
    {
        return array_key_exists($parameter, $this->parameters);
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function jsonSerialize(): array
    {
        return $this->parameters;
    }
}
