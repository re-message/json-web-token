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

namespace RM\Standard\Jwt\Key\Loader;

use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Resource\ResourceInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
interface ResourceLoaderInterface
{
    /**
     * Load keys from all pushed resources via key loader.
     *
     * @return iterable<KeyInterface>
     */
    public function load(): iterable;

    /**
     * Add resource to load.
     */
    public function pushResource(ResourceInterface $resource): void;
}
