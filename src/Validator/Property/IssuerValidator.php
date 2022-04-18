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

namespace RM\Standard\Jwt\Validator\Property;

use RM\Standard\Jwt\Exception\IssuerViolationException;
use RM\Standard\Jwt\Property\Payload\Issuer;
use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\PropertyTarget;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @see Issuer
 */
class IssuerValidator implements PropertyValidatorInterface
{
    protected readonly array $issuers;

    public function __construct(array|string $issuers)
    {
        if (is_string($issuers)) {
            trigger_deprecation(
                'relmsg/json-web-token',
                '0.2.4',
                'Using a string as "%s" parameter is deprecated and will cause an error on 0.3.0. Use array instead.',
                static::class
            );

            $issuers = [$issuers];
        }

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
