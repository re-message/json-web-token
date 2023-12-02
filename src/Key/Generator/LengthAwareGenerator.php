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

namespace RM\Standard\Jwt\Key\Generator;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract readonly class LengthAwareGenerator implements KeyGeneratorInterface
{
    final public const string LENGTH_OPTION = 'length';

    public function __construct(
        private int $defaultLength,
        private int $minimalLength = 0,
    ) {}

    final protected function resolveLength(array $options): int
    {
        $length = $options[self::LENGTH_OPTION] ?? null;
        if (null === $length) {
            return $this->defaultLength;
        }

        if ($length <= $this->minimalLength) {
            return $this->minimalLength;
        }

        return $length;
    }
}
