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

namespace RM\Standard\Jwt\Handler;

use RM\Standard\Jwt\Exception\IncorrectPropertyTypeException;
use RM\Standard\Jwt\Identifier\IdentifierGeneratorInterface;
use RM\Standard\Jwt\Identifier\UniqIdGenerator;
use RM\Standard\Jwt\Property\Payload\Identifier;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;
use RM\Standard\Jwt\Token\PropertyInterface;
use UnexpectedValueException;

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

    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    public function getPropertyName(): string
    {
        return Identifier::NAME;
    }

    protected function generateProperty(): Identifier
    {
        $identifier = $this->identifierGenerator->generate();
        $this->tokenStorage->put($identifier, $this->duration);

        return new Identifier($identifier);
    }

    protected function validateProperty(PropertyInterface $property): bool
    {
        if (!$property instanceof Identifier) {
            $message = sprintf('%s can handle only %s.', static::class, $property::class);
            throw new UnexpectedValueException($message);
        }

        $value = $property->getValue();
        if (!is_string($value)) {
            throw new IncorrectPropertyTypeException('string', gettype($value), $this->getPropertyName());
        }

        return $this->tokenStorage->has($value);
    }
}
