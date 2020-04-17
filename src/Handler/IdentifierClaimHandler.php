<?php
/**
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Handler;

use InvalidArgumentException;
use RM\Standard\Jwt\Exception\IncorrectClaimTypeException;
use RM\Standard\Jwt\Identifier\IdentifierGeneratorInterface;
use RM\Standard\Jwt\Identifier\UniqIdGenerator;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;
use RM\Standard\Jwt\Token\Payload;

/**
 * Class IdentifierClaimHandler provides processing for { @see Payload::CLAIM_IDENTIFIER } claim.
 *
 * @package RM\Standard\Jwt\Handler
 * @author  h1karo <h1karo@outlook.com>
 */
class IdentifierClaimHandler extends AbstractClaimHandler
{
    protected IdentifierGeneratorInterface $identifierGenerator;
    protected TokenStorageInterface $tokenStorage;

    /**
     * Duration of token in seconds. By default is 1 hour.
     * For security reason, cannot be infinite.
     *
     * @var int
     */
    protected int $duration = 60 * 60;

    public function __construct(
        IdentifierGeneratorInterface $generator = null,
        TokenStorageInterface $storage = null,
        int $duration = 60 * 60
    ) {
        $this->identifierGenerator = $generator ?? new UniqIdGenerator();
        $this->tokenStorage = $storage ?? new RuntimeTokenStorage();
        $this->duration = $duration;
    }

    /**
     * @inheritDoc
     */
    public function getClaim(): string
    {
        return Payload::CLAIM_IDENTIFIER;
    }

    /**
     * @inheritDoc
     */
    protected function generateValue(): string
    {
        if ($this->identifierGenerator === null) {
            throw new InvalidArgumentException(
                sprintf(
                    'To use %s required set up the identifier generator.',
                    static::class
                )
            );
        }

        $identifier = $this->identifierGenerator->generate();

        if ($this->tokenStorage === null) {
            throw new InvalidArgumentException(sprintf('To use %s required set up the token storage.', static::class));
        }

        $this->tokenStorage->put($identifier, $this->duration);
        return $identifier;
    }

    /**
     * @inheritDoc
     */
    protected function validateValue($value): bool
    {
        if ($this->tokenStorage === null) {
            throw new InvalidArgumentException(sprintf('To use %s required set up the token storage.', static::class));
        }

        if (!is_string($value)) {
            throw new IncorrectClaimTypeException('string', gettype($value), $this->getClaim());
        }

        return $this->tokenStorage->has($value);
    }
}