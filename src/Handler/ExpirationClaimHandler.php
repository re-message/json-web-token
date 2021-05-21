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

use RM\Standard\Jwt\Exception\ExpirationViolationException;
use RM\Standard\Jwt\Exception\IncorrectClaimTypeException;
use RM\Standard\Jwt\Token\Payload;

/**
 * Class ExpirationClaimHandler
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class ExpirationClaimHandler extends AbstractClaimHandler
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
    public function getClaim(): string
    {
        return Payload::CLAIM_EXPIRATION;
    }

    /**
     * @inheritDoc
     */
    protected function generateValue(): int
    {
        return time() + $this->duration;
    }

    /**
     * @inheritDoc
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
