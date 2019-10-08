<?php
/**
 * Relations Messenger Json Web Token Implementation
 *
 * @link      https://gitlab.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/json-web-token
 * @copyright Copyright (c) 2018-2019 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 */

namespace RM\Security\Jwt\Handler;

use RM\Security\Jwt\Exception\ExpirationViolationException;
use RM\Security\Jwt\Exception\IncorrectClaimTypeException;
use RM\Security\Jwt\Token\Payload;

/**
 * Class ExpirationClaimHandler
 *
 * @package RM\Security\Jwt\Handler
 * @author  h1karo <h1karo@outlook.com>
 * @Annotation
 */
class ExpirationClaimHandler extends AbstractClaimHandler
{
    use LeewayHandlerTrait;

    /**
     * Duration of token in seconds. By default is 1 hour.
     * For security reason, cannot be infinite.
     *
     * @var float|int
     */
    public $duration = 60 * 60;

    /**
     * {@inheritDoc}
     */
    public function getClaim(): string
    {
        return Payload::CLAIM_EXPIRATION;
    }

    /**
     * {@inheritDoc}
     */
    protected function generateValue(): int
    {
        return time() + $this->duration;
    }

    /**
     * {@inheritDoc}
     */
    protected function validateValue($value): bool
    {
        if (!is_int($value)) {
            throw new IncorrectClaimTypeException('integer', gettype($value), $this->getClaim());
        }

        if (time() > $value + $this->getLeeway()) {
            throw new ExpirationViolationException($this);
        }

        return true;
    }
}