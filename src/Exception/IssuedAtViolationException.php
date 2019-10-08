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

namespace RM\Security\Jwt\Exception;

use RM\Security\Jwt\Handler\ExpirationClaimHandler;
use RM\Security\Jwt\Handler\IssuedAtClaimHandler;
use Throwable;

/**
 * Class ExpirationViolationException
 *
 * @package RM\Security\Jwt\Exception
 * @author  h1karo <h1karo@outlook.com>
 */
class IssuedAtViolationException extends ClaimViolationException
{
    /**
     * ExpirationViolationException constructor.
     *
     * @param IssuedAtClaimHandler $claimHandler
     * @param Throwable|null       $previous
     */
    public function __construct(IssuedAtClaimHandler $claimHandler, Throwable $previous = null)
    {
        parent::__construct("This token is issued in the future.", $claimHandler, $previous);
    }
}