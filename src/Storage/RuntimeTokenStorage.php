<?php
/**
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://gitlab.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Security\Jwt\Storage;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class RuntimeTokenStorage implement runtime (in-memory) storage
 *
 * @package RM\Security\Jwt\Storage
 * @author  h1karo <h1karo@outlook.com>
 */
class RuntimeTokenStorage implements TokenStorageInterface
{
    private ArrayCollection $runtime;

    /**
     * RuntimeTokenStorage constructor.
     */
    public function __construct()
    {
        $this->runtime = new ArrayCollection();
    }

    /**
     * Checks if token id exists in storage
     *
     * @param string $tokenId
     *
     * @return bool
     */
    public function has(string $tokenId): bool
    {
        if ($this->runtime->containsKey($tokenId)) {
            return $this->runtime->get($tokenId) >= time();
        }

        return false;
    }

    /**
     * Adds token id in storage on some duration (ttl)
     *
     * @param string $tokenId
     * @param int    $duration
     */
    public function put(string $tokenId, int $duration): void
    {
        $this->runtime->set($tokenId, time() + $duration);
    }

    /**
     * Revokes token
     *
     * @param string $tokenId
     */
    public function revoke(string $tokenId): void
    {
        if ($this->runtime->containsKey($tokenId)) {
            $this->runtime->remove($tokenId);
        }
    }
}