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

namespace RM\Standard\Jwt\Handler;

use Doctrine\Common\Collections\ArrayCollection;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @template-implements ArrayCollection<int, TokenHandlerInterface>
 */
class TokenHandlerList extends ArrayCollection implements TokenHandlerInterface
{
    /**
     * @param TokenHandlerInterface[] $handlers
     */
    public function __construct(array $handlers = [])
    {
        parent::__construct($handlers);
    }

    public function generate(TokenInterface $token): void
    {
        /** @var TokenHandlerInterface $handler */
        foreach ($this as $handler) {
            $handler->generate($token);
        }
    }

    public function validate(TokenInterface $token): bool
    {
        /** @var TokenHandlerInterface $handler */
        foreach ($this as $handler) {
            if (!$handler->validate($token)) {
                return false;
            }
        }

        return true;
    }
}
