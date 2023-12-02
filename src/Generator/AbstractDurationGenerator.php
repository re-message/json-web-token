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

namespace RM\Standard\Jwt\Generator;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract readonly class AbstractDurationGenerator implements PropertyGeneratorInterface
{
    final public const float DEFAULT_DURATION = 60 * 60;
    final public const int MINIMAL_DURATION = 0;

    /**
     * Duration of token in seconds. By default, 1 hour.
     * For security reason, cannot be infinite or negative.
     */
    private int $duration;

    public function __construct(int $duration = self::DEFAULT_DURATION)
    {
        $this->duration = $duration;
    }

    final protected function getDuration(): int
    {
        if (PHP_INT_MAX === $this->duration) {
            return self::DEFAULT_DURATION;
        }

        if (self::MINIMAL_DURATION >= $this->duration) {
            return self::MINIMAL_DURATION;
        }

        return $this->duration;
    }
}
