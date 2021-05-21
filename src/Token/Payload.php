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

namespace RM\Standard\Jwt\Token;

use RM\Standard\Jwt\Handler\ExpirationClaimHandler;
use RM\Standard\Jwt\Handler\IdentifierClaimHandler;
use RM\Standard\Jwt\Handler\IssuedAtClaimHandler;
use RM\Standard\Jwt\Handler\IssuerClaimHandler;
use RM\Standard\Jwt\Handler\NotBeforeClaimHandler;
use RM\Standard\Jwt\Storage\TokenStorageInterface;

/**
 * Class Payload
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class Payload extends ClaimCollection
{
    /**
     * Issuer is a unique identity of token generator server, authentication server or security server.
     * You can set this claim to check where token generated.
     * It is maybe helps you if you use several servers with own token id {@see Payload::CLAIM_IDENTIFIER} cache server {@see TokenStorageInterface}.
     * We recommend to set this claim.
     *
     * @see IssuerClaimHandler The manager for this claim.
     */
    public const CLAIM_ISSUER = 'iss';
    /**
     * Subject is a unique identity of application who wants to get access to the audience {@see Payload::CLAIM_AUDIENCE}.
     * It is required claim.
     * No handler for this claim because the token service processes it directly.
     */
    public const CLAIM_SUBJECT = 'sub';
    /**
     * Audience is a unique identity of object token provides access to.
     * It is required claim. May have same value as {@see Payload::CLAIM_SUBJECT} claim.
     * No handler for this claim because the token service processes it directly. But you can create your own.
     */
    public const CLAIM_AUDIENCE = 'aud';
    /**
     * Expiration is a time in UNIX format when token expires.
     * It is required claim.
     *
     * @see ExpirationClaimHandler The manager for this claim.
     */
    public const CLAIM_EXPIRATION = 'exp';
    /**
     * Not before time is a time in UNIX format before which the token is not valid.
     *
     * @see NotBeforeClaimHandler The manager for this claim.
     */
    public const CLAIM_NOT_BEFORE = 'nbf';
    /**
     * Issued at time is a time in UNIX format of token creation.
     * Often a value of this claim equals a value of {@see CLAIM_NOT_BEFORE} claim.
     *
     * @see IssuedAtClaimHandler The manager for this claim.
     */
    public const CLAIM_ISSUED_AT = 'iat';
    /**
     * Token identifier is unique sequence to provide revoke functional.
     * We recommend to set this claim.
     *
     * @see IdentifierClaimHandler The manager for this claim.
     */
    public const CLAIM_IDENTIFIER = 'jti';
}
