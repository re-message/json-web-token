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

namespace RM\Standard\Jwt\Storage;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class RuntimeTokenStorage implement runtime (in-memory) storage
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class RuntimeTokenStorage implements TokenStorageInterface
{
    private ArrayCollection $runtime;

    public function __construct()
    {
        $this->runtime = new ArrayCollection();
    }

    public function has(string $tokenId): bool
    {
        if ($this->runtime->containsKey($tokenId)) {
            return $this->runtime->get($tokenId) >= time();
        }

        return false;
    }

    public function put(string $tokenId, int $duration): void
    {
        $this->runtime->set($tokenId, time() + $duration);
    }

    public function revoke(string $tokenId): void
    {
        if ($this->runtime->containsKey($tokenId)) {
            $this->runtime->remove($tokenId);
        }
    }
}
