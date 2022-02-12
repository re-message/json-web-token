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

use RM\Standard\Jwt\Property\Payload\Expiration;
use RM\Standard\Jwt\Token\PropertyTarget;

/**
 * Class ExpirationGenerator
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class ExpirationGenerator implements PropertyGeneratorInterface
{
    /**
     * Duration of token in seconds. By default, 1 hour.
     * For security reason, cannot be infinite.
     */
    protected int $duration;

    public function __construct(int $duration = 60 * 60)
    {
        $this->duration = $duration;
    }

    public function getPropertyName(): string
    {
        return Expiration::NAME;
    }

    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    public function generate(): Expiration
    {
        $value = time() + $this->duration;

        return new Expiration($value);
    }
}
