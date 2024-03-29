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

namespace RM\Standard\Jwt\Token;

use Override;
use RM\Standard\Jwt\Property\Payload\ClaimInterface;
use RM\Standard\Jwt\Property\PropertyBag;
use RM\Standard\Jwt\Property\PropertyInterface;
use UnexpectedValueException;

/**
 * @template-extends PropertyBag<ClaimInterface>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
readonly class Payload extends PropertyBag
{
    #[Override]
    public function get(string $name): ClaimInterface
    {
        $property = parent::get($name);
        if (!$property instanceof ClaimInterface) {
            throw new UnexpectedValueException('Expects a claim.');
        }

        return $property;
    }

    #[Override]
    public function find(string $name): ?ClaimInterface
    {
        $property = parent::find($name);
        if (null !== $property && !$property instanceof ClaimInterface) {
            throw new UnexpectedValueException('Expects a claim.');
        }

        return $property;
    }

    #[Override]
    public function set(PropertyInterface $property): void
    {
        if (!$property instanceof ClaimInterface) {
            throw new UnexpectedValueException('Expects a claim.');
        }

        parent::set($property);
    }
}
