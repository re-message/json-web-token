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

namespace RM\Standard\Jwt\Token;

use InvalidArgumentException;
use RM\Standard\Jwt\Exception\PropertyNotFoundException;
use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Property\Header\HeaderParameterInterface;
use RM\Standard\Jwt\Property\Header\Type;
use UnexpectedValueException;

/**
 * @template-extends PropertyBag<HeaderParameterInterface>
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class Header extends PropertyBag
{
    /**
     * @param HeaderParameterInterface[] $parameters
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct([new Type('JWT')]);

        foreach ($parameters as $parameter) {
            $this->set($parameter);
        }

        if (!$this->has(Algorithm::NAME)) {
            $message = sprintf('Any JSON Web Token must have the algorithm parameter (`%s`).', Algorithm::NAME);

            throw new InvalidArgumentException($message);
        }
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function get(string $name): HeaderParameterInterface
    {
        $property = parent::get($name);
        if (!$property instanceof HeaderParameterInterface) {
            throw new UnexpectedValueException('Expects a header parameter.');
        }

        return $property;
    }

    public function find(string $name): ?HeaderParameterInterface
    {
        $property = parent::find($name);
        if (null !== $property && !$property instanceof HeaderParameterInterface) {
            throw new UnexpectedValueException('Expects a header parameter.');
        }

        return $property;
    }

    public function set(PropertyInterface $property): void
    {
        if (!$property instanceof HeaderParameterInterface) {
            throw new UnexpectedValueException('Expects a header parameter.');
        }

        parent::set($property);
    }
}
