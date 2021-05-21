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

use RM\Standard\Jwt\Exception\IncorrectClaimTypeException;
use RM\Standard\Jwt\Exception\NotBeforeViolationException;
use RM\Standard\Jwt\Token\Payload;

/**
 * Class IssuedAtClaimHandler
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class NotBeforeClaimHandler extends AbstractClaimHandler
{
    use LeewayHandlerTrait;

    /**
     * @inheritDoc
     */
    public function getClaim(): string
    {
        return Payload::CLAIM_NOT_BEFORE;
    }

    /**
     * @inheritDoc
     */
    protected function generateValue(): int
    {
        return time();
    }

    /**
     * @inheritDoc
     */
    protected function validateValue($value): bool
    {
        if (!is_int($value)) {
            throw new IncorrectClaimTypeException('integer', gettype($value), $this->getClaim());
        }

        if (time() < $value - $this->getLeeway()) {
            throw new NotBeforeViolationException($this);
        }

        return true;
    }
}
