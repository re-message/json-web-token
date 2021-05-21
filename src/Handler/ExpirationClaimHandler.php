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

use RM\Standard\Jwt\Claim\Expiration;
use RM\Standard\Jwt\Exception\ExpirationViolationException;
use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Token\PropertyInterface;
use UnexpectedValueException;

/**
 * Class ExpirationClaimHandler
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class ExpirationClaimHandler extends AbstractPropertyHandler
{
    use LeewayHandlerTrait;

    /**
     * Duration of token in seconds. By default is 1 hour.
     * For security reason, cannot be infinite.
     */
    protected int $duration = 60 * 60;

    public function __construct(int $duration = 60 * 60, int $leeway = 0)
    {
        $this->duration = $duration;
        $this->leeway = $leeway;
    }

    /**
     * @inheritDoc
     */
    public function getPropertyName(): string
    {
        return Expiration::NAME;
    }

    /**
     * @inheritDoc
     */
    protected function generateProperty(): Expiration
    {
        $value = time() + $this->duration;

        return new Expiration($value);
    }

    /**
     * @inheritDoc
     */
    protected function validateProperty(PropertyInterface $property): bool
    {
        if (!$property instanceof Expiration) {
            $message = sprintf('%s can handle only %s.', self::class, Expiration::class);
            throw new UnexpectedValueException($message);
        }

        $value = $property->getValue();
        if (!is_int($value)) {
            throw new IncorrectPropertyTypeException('integer', gettype($value), $this->getPropertyName());
        }

        if (time() > $value + $this->getLeeway()) {
            throw new ExpirationViolationException($this);
        }

        return true;
    }
}
