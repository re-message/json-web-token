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

namespace RM\Standard\Jwt\Identifier;

use Laminas\Math\Rand;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
final class LaminasRandGenerator implements IdentifierGeneratorInterface
{
    private const MIN_LENGTH = 32;

    public function __construct(
        private readonly int $length = 64,
    ) {
    }

    /**
     * @see getLength()
     */
    public function generate(): string
    {
        return Rand::getString($this->getLength());
    }

    /**
     * Returns length for generation random string.
     */
    private function getLength(): int
    {
        if ($this->length <= self::MIN_LENGTH) {
            $message = sprintf(
                'Length to generation random identifier can not be less than %s, got %s.',
                self::MIN_LENGTH,
                $this->length
            );
            @trigger_error($message, E_USER_NOTICE);

            return self::MIN_LENGTH;
        }

        return $this->length;
    }
}
