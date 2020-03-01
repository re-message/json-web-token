<?php
/**
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://gitlab.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 */
class ExpirationClaimHandler extends AbstractClaimHandler
{
    use LeewayHandlerTrait;

    /**
     * Duration of token in seconds. By default is 1 hour.
     * For security reason, cannot be infinite.
     *
     * @var int
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