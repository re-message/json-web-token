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

namespace RM\Standard\Jwt\Generator;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
abstract class AbstractDurationGenerator implements PropertyGeneratorInterface
{
    public const DEFAULT_DURATION = 60 * 60;
    public const MINIMAL_DURATION = 0;

    /**
     * Duration of token in seconds. By default, 1 hour.
     * For security reason, cannot be infinite or negative.
     */
    private readonly int $duration;

    public function __construct(int $duration = self::DEFAULT_DURATION)
    {
        $this->duration = $duration;
    }

    final protected function getDuration(): int
    {
        if ($this->duration === INF) {
            return self::DEFAULT_DURATION;
        }

        if (self::MINIMAL_DURATION >= $this->duration) {
            return self::MINIMAL_DURATION;
        }

        return $this->duration;
    }
}
