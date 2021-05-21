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

namespace RM\Standard\Jwt\Service;

use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Event\TokenPreSignEvent;
use RM\Standard\Jwt\Event\TokenSignEvent;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Exception\PropertyViolationException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Token\SignatureToken;

/**
 * Interface SignatureServiceInterface
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
interface SignatureServiceInterface
{
    /**
     * Sign token with this key.
     * This method triggers some events that call handlers for some claims.
     *
     * @param SignatureToken $token
     * @param KeyInterface   $key
     *
     * @return SignatureToken
     * @throws InvalidTokenException
     * @see TokenPreSignEvent
     * @see TokenSignEvent
     */
    public function sign(SignatureToken $token, KeyInterface $key): SignatureToken;

    /**
     * Verify that token is valid and signature is exist and correct.
     *
     * @param SignatureToken $token
     * @param KeyInterface   $key
     *
     * @return bool
     * @throws PropertyViolationException
     * @throws InvalidTokenException
     */
    public function verify(SignatureToken $token, KeyInterface $key): bool;

    /**
     * Returns algorithm by name from algorithm manager.
     *
     * @param string $name
     *
     * @return SignatureAlgorithmInterface
     */
    public function findAlgorithm(string $name): SignatureAlgorithmInterface;
}
