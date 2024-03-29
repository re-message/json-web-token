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

namespace RM\Standard\Jwt\Key\Resolver;

use Override;
use RM\Standard\Jwt\Exception\KeyIdNotFound;
use RM\Standard\Jwt\Exception\KeyOperationNotMatch;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\KeyOperation;
use RM\Standard\Jwt\Key\Parameter\Operations;
use RM\Standard\Jwt\Key\Storage\KeyStorageInterface;
use RM\Standard\Jwt\Key\Transformer\PublicKey\PublicKeyTransformerInterface;
use RM\Standard\Jwt\Property\Header\KeyId;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
readonly class StorageKeyResolver implements KeyResolverInterface
{
    public function __construct(
        private KeyStorageInterface $storage,
        private PublicKeyTransformerInterface $transformer,
    ) {}

    #[Override]
    public function resolve(TokenInterface $token, KeyOperation $operation): KeyInterface
    {
        $idParameter = $token->getHeader()->find(KeyId::NAME);
        if (null === $idParameter) {
            throw new KeyIdNotFound();
        }

        $id = $idParameter->getValue();
        $key = $this->storage->get($id);

        if ($this->matchesOperation($key, $operation)) {
            return $key;
        }

        if (!$this->transformer->supports($key->getType())) {
            throw new KeyOperationNotMatch();
        }

        $publicKey = $this->transformer->transform($key);
        if (!$this->matchesOperation($publicKey, $operation)) {
            throw new KeyOperationNotMatch();
        }

        return $key;
    }

    protected function matchesOperation(KeyInterface $key, KeyOperation $operation): bool
    {
        if (!$key->has(Operations::NAME)) {
            return true;
        }

        /** @var Operations $operationParameter */
        $operationParameter = $key->get(Operations::NAME);
        $operations = $operationParameter->toEnum();

        return in_array($operation, $operations, true);
    }
}
