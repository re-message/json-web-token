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

namespace RM\Standard\Jwt\Validator\Property;

use RM\Standard\Jwt\Exception\IssuerViolationException;
use RM\Standard\Jwt\Property\Payload\Issuer;
use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\PropertyTarget;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see Issuer
 */
class IssuerValidator implements PropertyValidatorInterface
{
    protected readonly array $issuers;

    public function __construct(array $issuers)
    {
        $this->issuers = $issuers;
    }

    public function getPropertyName(): string
    {
        return Issuer::NAME;
    }

    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    public function validate(PropertyInterface $property): bool
    {
        if (!$property instanceof Issuer) {
            $message = sprintf('%s can handle only %s.', static::class, Issuer::class);

            throw new UnexpectedValueException($message);
        }

        $value = $property->getValue();
        if (!in_array($value, $this->issuers, true)) {
            throw new IssuerViolationException($this);
        }

        return true;
    }
}
