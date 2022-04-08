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

namespace RM\Standard\Jwt\Service;

use RM\Standard\Jwt\Event\TokenPreSignEvent;
use RM\Standard\Jwt\Event\TokenSignEvent;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Exception\PropertyViolationException;
use RM\Standard\Jwt\Signature\SignatureToken;

/**
 * Interface SignatureServiceInterface
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
interface SignatureServiceInterface
{
    /**
     * Signs token.
     *
     * @throws InvalidTokenException
     * @see TokenPreSignEvent
     * @see TokenSignEvent
     */
    public function sign(SignatureToken $token): SignatureToken;

    /**
     * Verify that token is valid and signature exist and correct.
     *
     * @throws PropertyViolationException
     * @throws InvalidTokenException
     */
    public function verify(SignatureToken $token): bool;
}
