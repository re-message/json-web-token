<?php
/*
 * This file is a part of Re Message Json Web Token implementation.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/json-web-token
 * @link      https://dev.remessage.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2023 Re Message
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
use Override;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
readonly class ChainValidator implements ValidatorInterface
{
    /**
     * @var Collection<int, ValidatorInterface>
     */
    private Collection $validators;

    /**
     * @param ValidatorInterface[] $validators
     */
    public function __construct(array $validators = [])
    {
        $this->validators = new ArrayCollection();

        foreach ($validators as $validator) {
            $this->pushValidator($validator);
        }
    }

    #[Override]
    public function validate(TokenInterface $token): bool
    {
        foreach ($this->validators as $validator) {
            if (!$validator->validate($token)) {
                return false;
            }
        }

        return true;
    }

    public function pushValidator(ValidatorInterface $validator): void
    {
        $this->validators->add($validator);
    }

    public function getValidators(): array
    {
        return $this->validators->toArray();
    }
}
