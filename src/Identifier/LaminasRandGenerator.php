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

namespace RM\Standard\Jwt\Identifier;

use InvalidArgumentException;
use Laminas\Math\Rand;
use Override;
use ParagonIE\ConstantTime\Base64UrlSafe;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
final class LaminasRandGenerator implements IdentifierGeneratorInterface
{
    private const MIN_LENGTH = 32;

    public function __construct(
        private readonly int $length = 64,
    ) {
        // @codeCoverageIgnoreStart
        if (!class_exists(Rand::class)) {
            $message = sprintf(
                '%s class not found. You need the laminas/laminas-math package to use this generator.',
                Rand::class
            );

            throw new InvalidArgumentException($message);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @see getLength()
     */
    #[Override]
    public function generate(): string
    {
        $bytes = Rand::getBytes($this->getLength());

        return Base64UrlSafe::encodeUnpadded($bytes);
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
