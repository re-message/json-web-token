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
use RM\Standard\Jwt\Algorithm\AlgorithmManager;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Exception\AlgorithmNotFoundException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Signer\Signer;
use RM\Standard\Jwt\Signer\SignerInterface;
use RM\Standard\Jwt\Token\SignatureToken;
use RM\Standard\Jwt\Validator\ChainValidator;
use RM\Standard\Jwt\Validator\ValidatorInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class SignatureService implements SignatureServiceInterface
{
    public function __construct(
        private readonly AlgorithmManager $algorithmManager,
        private readonly SignerInterface $signer = new Signer(),
        private readonly ValidatorInterface $validator = new ChainValidator(),
    ) {}

    final public function sign(SignatureToken $token, KeyInterface $key): SignatureToken
    {
        $algorithm = $this->findAlgorithm($token->getAlgorithm());

        return $this->signer->sign($token, $algorithm, $key);
    }

    final public function verify(SignatureToken $token, KeyInterface $key): bool
    {
        if (!$token->isSigned()) {
            return false;
        }

        if (!$this->validator->validate($token)) {
            return false;
        }

        $algorithm = $this->findAlgorithm($token->getAlgorithm());

        $resignedToken = $this->signer->sign($token, $algorithm, $key);

        return hash_equals($token->getSignature(), $resignedToken->getSignature());
    }

    /**
     * @throws AlgorithmNotFoundException
     */
    public function findAlgorithm(string $name): SignatureAlgorithmInterface
    {
        $algorithm = $this->algorithmManager->get($name);

        if (!$algorithm instanceof SignatureAlgorithmInterface) {
            $message = sprintf(
                'Signature algorithm must implement %1$s, given %2$s.',
                SignatureAlgorithmInterface::class,
                $algorithm::class
            );
            throw new InvalidArgumentException($message);
        }

        return $algorithm;
    }

    public function getAlgorithmManager(): AlgorithmManager
    {
        return $this->algorithmManager;
    }
}
