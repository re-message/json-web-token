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

namespace RM\Standard\Jwt\Storage;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class RuntimeTokenStorage implement runtime (in-memory) storage.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
