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

namespace RM\Standard\Jwt\Validator\Property;

abstract class AbstractLeewayValidator implements PropertyValidatorInterface
{
    public const DEFAULT_LEEWAY = 0;
    public const DEFAULT_MAX_LEEWAY = 2 * 60;
    public const MINIMAL_LEEWAY = 0;

    /**
     * Max leeway value.
     * By default is 2 minutes.
     */
    protected int $maxLeeway = self::DEFAULT_MAX_LEEWAY;

    /**
     * Allowed leeway in seconds. By default, 0.
     * For security reason, can not be more than max leeway or negative.
     */
    private readonly int $leeway;

    public function __construct(int $leeway = self::DEFAULT_LEEWAY)
    {
        $this->leeway = $leeway;
    }

    final protected function getLeeway(): int
    {
        if (self::MINIMAL_LEEWAY >= $this->leeway) {
            return self::MINIMAL_LEEWAY;
        }

        if ($this->leeway >= $this->maxLeeway) {
            return $this->maxLeeway;
        }

        return $this->leeway;
    }
}
