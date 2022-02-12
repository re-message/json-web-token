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

use InvalidArgumentException;
use RM\Standard\Jwt\Algorithm\AlgorithmResolverInterface;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Exception\AlgorithmNotFoundException;
use RM\Standard\Jwt\Key\Resolver\KeyResolverInterface;
use RM\Standard\Jwt\Signer\Signer;
use RM\Standard\Jwt\Signer\SignerInterface;
use RM\Standard\Jwt\Token\SignatureToken;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
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
        $algorithm = $this->resolveAlgorithm($token);
        $key = $this->keyResolver->resolve($token);

        return $this->signer->sign($token, $algorithm, $key);
    }

    final public function verify(SignatureToken $token): bool
    {
        $algorithm = $this->resolveAlgorithm($token);
        $key = $this->keyResolver->resolve($token);

        return $this->signer->verify($token, $algorithm, $key);
    }

    /**
     * @throws AlgorithmNotFoundException
     */
    protected function resolveAlgorithm(SignatureToken $token): SignatureAlgorithmInterface
    {
        $algorithm = $this->algorithmResolver->resolve($token);
        if (!$algorithm instanceof SignatureAlgorithmInterface) {
            $this->throwInvalidAlgorithmException($algorithm::class);
        }

        return $algorithm;
    }

    protected function throwInvalidAlgorithmException(string $algorithm): void
    {
        $expect = SignatureAlgorithmInterface::class;
        $message = sprintf(
            'Signature algorithm must implement %1$s, given %2$s.',
            $expect,
            $algorithm
        );
        throw new InvalidArgumentException($message);
    }
}
