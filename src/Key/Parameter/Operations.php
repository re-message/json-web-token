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

namespace RM\Standard\Jwt\Key\Parameter;

use RM\Standard\Jwt\Key\KeyOperation;

/**
 * @template-extends KeyParameter<array<int, string>>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class Operations extends KeyParameter
{
    public const NAME = 'key_ops';

    /**
     * @param array<int, string> $operations
     */
    public function __construct(array $operations)
    {
        parent::__construct(self::NAME, $operations);
    }

    public function toEnum(): array
    {
        $operations = [];
        $array = $this->getValue();
        foreach ($array as $value) {
            $operations[] = KeyOperation::from($value);
        }

        return $operations;
    }

    public static function fromEnum(array $operations): static
    {
        $toString = static fn (KeyOperation $operation) => $operation->value;
        $array = array_map($toString, $operations);

        return new static($array);
    }
}
