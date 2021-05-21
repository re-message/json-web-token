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

use RM\Standard\Jwt\Claim\Issuer;
use RM\Standard\Jwt\Exception\IssuerViolationException;

/**
 * Class IssuedAtClaimHandler
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class IssuerClaimHandler extends AbstractPropertyHandler
{
    /**
     * The identifier of server which issued the token
     */
    protected string $issuer;

    public function __construct(string $issuer)
    {
        $this->issuer = $issuer;
    }

    /**
     * @inheritDoc
     */
    public function getPropertyClass(): string
    {
        return Issuer::class;
    }

    /**
     * @inheritDoc
     */
    protected function generateProperty(): Issuer
    {
        return new Issuer($this->issuer);
    }

    /**
     * @inheritDoc
     */
    protected function validateValue(mixed $value): bool
    {
        if ($this->issuer !== $value) {
            throw new IssuerViolationException($this);
        }

        return true;
    }
}
