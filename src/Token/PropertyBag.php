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

namespace RM\Standard\Jwt\Token;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PropertyBag
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @template-implements ArrayCollection<int, PropertyInterface>
 */
abstract class PropertyBag extends ArrayCollection
{
    public function __construct(array $parameters = [], array $defaults = [])
    {
        parent::__construct(array_merge($defaults, $parameters));
    }
}
