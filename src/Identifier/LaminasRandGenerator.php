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

namespace RM\Standard\Jwt\Identifier;

use Laminas\Math\Rand;

/**
 * Class LaminasRandGenerator
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
final class LaminasRandGenerator implements IdentifierGeneratorInterface
{
    private const MIN_LENGTH = 32;

    protected int $length = 64;

    public function __construct(int $length = 64)
    {
        $this->length = $length;
    }

    /**
     * @return string
     * @see getLength()
     */
    public function generate(): string
    {
        return Rand::getString($this->getLength());
    }

    /**
     * Returns length for generation random string.
     *
     * @return int
     */
    private function getLength(): int
    {
        if ($this->length <= self::MIN_LENGTH) {
            @trigger_error(
                sprintf(
                    'Length to generation random identifier can not be less then %s, got %s.',
                    self::MIN_LENGTH,
                    $this->length
                ),
                E_USER_NOTICE
            );

            return self::MIN_LENGTH;
        }

        return $this->length;
    }
}
