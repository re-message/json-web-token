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

namespace RM\Standard\Jwt\Token;

use InvalidArgumentException;
use RM\Standard\Jwt\Property\Header\Algorithm;
use RM\Standard\Jwt\Property\Header\HeaderParameterInterface;
use RM\Standard\Jwt\Property\Header\Type;
use UnexpectedValueException;

/**
 * @template-extends PropertyBag<HeaderParameterInterface>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
     * @inheritDoc
     */
    public function get(string $name): HeaderParameterInterface
    {
        $property = parent::get($name);
        if (!$property instanceof HeaderParameterInterface) {
            throw new UnexpectedValueException('Expects a header parameter.');
        }

        return $property;
    }

    /**
     * @inheritDoc
     */
    public function find(string $name): ?HeaderParameterInterface
    {
        $property = parent::find($name);
        if (null !== $property && !$property instanceof HeaderParameterInterface) {
            throw new UnexpectedValueException('Expects a header parameter.');
        }

        return $property;
    }

    /**
     * @inheritDoc
     */
    public function set(PropertyInterface $property): void
    {
        if (!$property instanceof HeaderParameterInterface) {
            throw new UnexpectedValueException('Expects a header parameter.');
        }

        parent::set($property);
    }
}
