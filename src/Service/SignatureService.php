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

namespace RM\Standard\Jwt\Service;

use RM\Standard\Jwt\Algorithm\AlgorithmResolverInterface;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Key\Resolver\KeyResolverInterface;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\Signer;
use RM\Standard\Jwt\Signature\SignerInterface;
use RM\Standard\Jwt\Validator\ValidatorInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @deprecated This class will be removed in 0.2.0. Use {@see SignerInterface} and {@see ValidatorInterface} instead.
 */
class SignatureService implements SignatureServiceInterface
{
    public function __construct(
        private readonly AlgorithmResolverInterface $algorithmResolver,
        private readonly KeyResolverInterface $keyResolver,
        private readonly SignerInterface $signer = new Signer(),
    ) {}

    final public function sign(SignatureToken $token): SignatureToken
    {
        $algorithm = $this->algorithmResolver->resolve($token, SignatureAlgorithmInterface::class);
        $key = $this->keyResolver->resolve($token);

        return $this->signer->sign($token, $algorithm, $key);
    }

    final public function verify(SignatureToken $token): bool
    {
        $algorithm = $this->algorithmResolver->resolve($token, SignatureAlgorithmInterface::class);
        $key = $this->keyResolver->resolve($token);

        return $this->signer->verify($token, $algorithm, $key);
    }
}
