<?php
/*
 * This file is a part of Re Message Json Web Token implementation.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/json-web-token
 * @link      https://dev.remessage.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Re Message
 * @author    Oleg Kozlov <h1karo@remessage.ru>
 * @license   Apache License 2.0
 * @license   https://legal.remessage.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RM\Standard\Jwt\Property\Header\HeaderParameterInterface;
use RM\Standard\Jwt\Property\Payload\ClaimInterface;
use RM\Standard\Jwt\Property\PropertyInterface;
use RM\Standard\Jwt\Property\PropertyTarget;
use RM\Standard\Jwt\Token\TokenInterface;
use RM\Standard\Jwt\Validator\Property\PropertyValidatorInterface;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class ChainPropertyValidator implements ValidatorInterface
{
    /**
     * @var Collection<int, PropertyValidatorInterface>
     */
    private readonly Collection $validators;

    /**
     * @param PropertyValidatorInterface[] $validators
     */
    public function __construct(array $validators = [])
    {
        $this->validators = new ArrayCollection();

        foreach ($validators as $validator) {
            $this->pushValidator($validator);
        }
    }

    public function validate(TokenInterface $token): bool
    {
        $headerProperties = $token->getHeader()->getProperties();
        $payloadProperties = $token->getPayload()->getProperties();

        /** @var PropertyInterface[] $properties */
        $properties = array_merge($headerProperties, $payloadProperties);

        foreach ($properties as $property) {
            $validator = $this->findValidator($property);
            if (null === $validator) {
                continue;
            }

            if (!$validator->validate($property)) {
                return false;
            }
        }

        return true;
    }

    public function pushValidator(PropertyValidatorInterface $validator): void
    {
        $this->validators->add($validator);
    }

    public function getValidators(): array
    {
        return $this->validators->toArray();
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
