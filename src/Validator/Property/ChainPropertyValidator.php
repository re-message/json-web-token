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

namespace RM\Standard\Jwt\Validator\Property;

use RM\Standard\Jwt\Handler\PropertyTarget;
use RM\Standard\Jwt\Handler\PropertyValidatorInterface;
use RM\Standard\Jwt\Property\Header\HeaderParameterInterface;
use RM\Standard\Jwt\Property\Payload\ClaimInterface;
use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\TokenInterface;
use RM\Standard\Jwt\Validator\ValidatorInterface;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class ChainPropertyValidator implements ValidatorInterface
{
    /**
     * @param PropertyValidatorInterface[] $validators
     */
    public function __construct(private array $validators = [])
    {
    }

    public function validate(TokenInterface $token): bool
    {
        $headerProperties = array_values($token->getHeader()->toArray());
        $payloadProperties = array_values($token->getPayload()->toArray());

        /** @var PropertyInterface[] $properties */
        $properties = array_merge($headerProperties, $payloadProperties);

        foreach ($properties as $property) {
            $validator = $this->findValidator($property);
            if ($validator === null) {
                continue;
            }

            if (!$validator->validate($property)) {
                return false;
            }
        }

        return true;
    }

    private function findValidator(PropertyInterface $property): ?PropertyValidatorInterface
    {
        foreach ($this->validators as $validator) {
            $target = $this->getPropertyTarget($property);
            $isSameTarget = $validator->getPropertyTarget() === $target;
            $isSameName = $validator->getPropertyName() === $property->getName();

            if ($isSameTarget && $isSameName) {
                return $validator;
            }
        }

        return null;
    }

    private function getPropertyTarget(PropertyInterface $property): PropertyTarget
    {
        return match ($property::class) {
            HeaderParameterInterface::class => PropertyTarget::HEADER,
            ClaimInterface::class => PropertyTarget::PAYLOAD,
            default => throw new UnexpectedValueException(),
        };
    }
}
