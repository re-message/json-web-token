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

use RM\Standard\Jwt\Claim\Identifier;
use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Identifier\IdentifierGeneratorInterface;
use RM\Standard\Jwt\Identifier\UniqIdGenerator;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;

/**
 * Class IdentifierClaimHandler provides processing for { @see Identifier } claim.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class IdentifierClaimHandler extends AbstractPropertyHandler
{
    protected IdentifierGeneratorInterface $identifierGenerator;
    protected TokenStorageInterface $tokenStorage;

    /**
     * Duration of token in seconds. By default, 1 hour.
     * For security reason, cannot be infinite.
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
    public function getPropertyClass(): string
    {
        return Identifier::class;
    }

    /**
     * @inheritDoc
     */
    protected function generateProperty(): Identifier
    {
        $identifier = $this->identifierGenerator->generate();
        $this->tokenStorage->put($identifier, $this->duration);

        return new Identifier($identifier);
    }

    /**
     * @inheritDoc
     */
    protected function validateValue(mixed $value): bool
    {
        if (!is_string($value)) {
            throw new IncorrectPropertyTypeException('string', gettype($value), $this->getPropertyName());
        }

        return $this->tokenStorage->has($value);
    }
}
