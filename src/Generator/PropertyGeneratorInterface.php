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

use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\PropertyTarget;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
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
     * Generate new value for current property
     */
    public function generate(): PropertyInterface;
}
