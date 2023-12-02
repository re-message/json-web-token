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

namespace RM\Standard\Jwt\Validator;

use Override;
use RM\Standard\Jwt\Algorithm\AlgorithmResolverInterface;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Key\KeyOperation;
use RM\Standard\Jwt\Key\Resolver\KeyResolverInterface;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Signature\Signer;
use RM\Standard\Jwt\Signature\SignerInterface;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class SignatureValidator implements ValidatorInterface
{
    public function __construct(
        private readonly AlgorithmResolverInterface $algorithmResolver,
        private readonly KeyResolverInterface $keyResolver,
        private readonly SignerInterface $signer = new Signer(),
    ) {}

    #[Override]
    public function validate(TokenInterface $token): bool
    {
        if (!$token instanceof SignatureToken) {
            return true;
        }

        if (!$token->isSecured()) {
            return false;
        }

        $algorithm = $this->algorithmResolver->resolve($token, SignatureAlgorithmInterface::class);
        $key = $this->keyResolver->resolve($token, KeyOperation::VERIFY);

        return $this->signer->verify($token, $algorithm, $key);
    }
}
