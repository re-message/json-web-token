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

namespace RM\Standard\Jwt\Handler;

use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Exception\NotBeforeViolationException;
use RM\Standard\Jwt\Property\Payload\NotBefore;
use RM\Standard\Jwt\Token\PropertyInterface;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class NotBeforeClaimHandler extends AbstractPropertyHandler
{
    use LeewayHandlerTrait;

    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    public function getPropertyName(): string
    {
        return NotBefore::NAME;
    }

    protected function generateProperty(): NotBefore
    {
        return new NotBefore(time());
    }

    protected function validateProperty(PropertyInterface $property): bool
    {
        if (!$property instanceof NotBefore) {
            $message = sprintf('%s can handle only %s.', static::class, $property::class);
            throw new UnexpectedValueException($message);
        }

        $value = $property->getValue();
        if (!is_int($value)) {
            throw new IncorrectPropertyTypeException('integer', gettype($value), $this->getPropertyName());
        }

        if (time() < $value - $this->getLeeway()) {
            throw new NotBeforeViolationException($this);
        }

        return true;
    }
}
