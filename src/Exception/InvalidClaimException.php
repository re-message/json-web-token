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

use Throwable;

/**
 * Class InvalidClaimException
 *
 * @package RM\Security\Jwt\Exception
 * @author  h1karo <h1karo@outlook.com>
 */
class InvalidClaimException extends InvalidTokenException
{
    /**
     * @var string
     */
    private $claim;

    public function __construct(string $message, string $claim, Throwable $previous = null)
    {
        parent::__construct($message, $previous);
        $this->claim = $claim;
    }

    /**
     * @return string
     */
    public function getClaim(): string
    {
        return $this->claim;
    }
}