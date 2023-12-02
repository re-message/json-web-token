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

use RM\Standard\Jwt\Property\PropertyInterface;
use RM\Standard\Jwt\Property\PropertyTarget;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
interface PropertyGeneratorInterface
{
    /**
     * Returns name of property to generate.
     */
    public function getPropertyName(): string;

    /**
     * Returns name of property to generate.
     */
    public function getPropertyTarget(): PropertyTarget;

    /**
     * Generate new value for current property.
     */
    public function generate(): PropertyInterface;
}
