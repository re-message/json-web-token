<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2021 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Handler;

use RM\Standard\Jwt\Claim\IssuedAt;
use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Exception\IssuedAtViolationException;
use RM\Standard\Jwt\Token\PropertyInterface;
use UnexpectedValueException;

/**
 * Class IssuedAtClaimHandler
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class IssuedAtClaimHandler extends AbstractPropertyHandler
{
    use LeewayHandlerTrait;

    /**
     * @inheritDoc
     */
    public function getPropertyName(): string
    {
        return IssuedAt::NAME;
    }

    /**
     * @inheritDoc
     */
    protected function generateProperty(): IssuedAt
    {
        return new IssuedAt(time());
    }

    /**
     * @inheritDoc
     */
    protected function validateProperty(PropertyInterface $property): bool
    {
        if (!$property instanceof IssuedAt) {
            $message = sprintf('%s can handle only %s.', self::class, IssuedAt::class);
            throw new UnexpectedValueException($message);
        }

        $value = $property->getValue();
        if (!is_int($value)) {
            throw new IncorrectPropertyTypeException('integer', gettype($value), $this->getPropertyName());
        }

        if (time() < $value - $this->getLeeway()) {
            throw new IssuedAtViolationException($this);
        }

        return true;
    }
}
