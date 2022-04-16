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

namespace RM\Standard\Jwt\Validator;

use RM\Standard\Jwt\Algorithm\AlgorithmResolverInterface;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Key\Resolver\KeyResolverInterface;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\Signer;
use RM\Standard\Jwt\Signature\SignerInterface;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class SignatureValidator implements ValidatorInterface
{
    public function __construct(
        private readonly AlgorithmResolverInterface $algorithmResolver,
        private readonly KeyResolverInterface $keyResolver,
        private readonly SignerInterface $signer = new Signer(),
    ) {
    }

    public function validate(TokenInterface $token): bool
    {
        if (!$token instanceof SignatureToken) {
            return true;
        }

        if (!$token->isSigned()) {
            return false;
        }

        $algorithm = $this->algorithmResolver->resolve($token, SignatureAlgorithmInterface::class);
        $key = $this->keyResolver->resolve($token);

        return $this->signer->verify($token, $algorithm, $key);
    }
}
