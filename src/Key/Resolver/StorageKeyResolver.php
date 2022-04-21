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

namespace RM\Standard\Jwt\Key\Resolver;

use RM\Standard\Jwt\Exception\KeyIdNotFound;
use RM\Standard\Jwt\Exception\KeyUseNotMatch;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\KeyUse;
use RM\Standard\Jwt\Key\Storage\KeyStorageInterface;
use RM\Standard\Jwt\Property\Header\KeyId;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class StorageKeyResolver implements KeyResolverInterface
{
    public function __construct(
        private readonly KeyStorageInterface $storage
    ) {
    }

    public function resolve(TokenInterface $token, KeyUse $use): KeyInterface
    {
        $idParameter = $token->getHeader()->find(KeyId::NAME);
        if (null === $idParameter) {
            throw new KeyIdNotFound();
        }

        $id = $idParameter->getValue();
        $key = $this->storage->get($id);
        $targetUse = KeyUse::from($key->get(KeyInterface::PARAM_USE));

        if ($use !== $targetUse) {
            throw new KeyUseNotMatch();
        }

        return $key;
    }
}
