<?php
/*
 * This file is a part of Re Message Json Web Token implementation.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/json-web-token
 * @link      https://dev.remessage.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Re Message
 * @author    Oleg Kozlov <h1karo@remessage.ru>
 * @license   Apache License 2.0
 * @license   https://legal.remessage.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Key\Transformer\SecLib;

use phpseclib3\Crypt\RSA;
use RM\Standard\Jwt\Exception\InvalidKeyException;
use RM\Standard\Jwt\Key\Factory\KeyFactoryInterface;
use RM\Standard\Jwt\Key\Factory\RsaKeyFactory;
use RM\Standard\Jwt\Key\Parameter\FirstCoefficient;
use RM\Standard\Jwt\Key\Parameter\FirstFactorExponent;
use RM\Standard\Jwt\Key\Parameter\FirstPrimeFactor;
use RM\Standard\Jwt\Key\Parameter\Modulus;
use RM\Standard\Jwt\Key\Parameter\PrivateExponent;
use RM\Standard\Jwt\Key\Parameter\PublicExponent;
use RM\Standard\Jwt\Key\Parameter\SecondFactorExponent;
use RM\Standard\Jwt\Key\Parameter\SecondPrimeFactor;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Transformer\PublicKey\PublicKeyTransformerInterface;
use RM\Standard\Jwt\Key\Transformer\PublicKey\RsaPublicKeyTransformer;

/**
 * @template T of RSA
 * @template-extends AbstractSecLibTransformer<T>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see RSA\Formats\Keys\Raw::load
 */
class RsaSecLibTransformer extends AbstractSecLibTransformer
{
    public function __construct(
        KeyFactoryInterface $factory = new RsaKeyFactory(),
        PublicKeyTransformerInterface $publicKeyTransformer = new RsaPublicKeyTransformer(),
    ) {
        parent::__construct($factory, $publicKeyTransformer);
    }

    protected function toComponents(array $parameters): array
    {
        $parameters = $this->filterRsaParameters($parameters);

        $publicExponent = $parameters[PublicExponent::NAME] ?? null;
        $modulus = $parameters[Modulus::NAME] ?? null;

        if (null === $publicExponent || null === $modulus) {
            throw new InvalidKeyException('Exponent and/or modulus not found.');
        }

        return array_map([$this, 'toComponent'], $parameters);
    }

    protected function fromComponents(array $components): array
    {
        $notNull = static fn (mixed $value) => null !== $value;

        /** @var array $flattedComponents */
        $flattedComponents = array_filter(
            [
                PublicExponent::NAME => $components['e'] ?? null,
                Modulus::NAME => $components['n'] ?? null,
                PrivateExponent::NAME => $components['d'] ?? null,
                FirstPrimeFactor::NAME => $components['primes'][1] ?? null,
                SecondPrimeFactor::NAME => $components['primes'][2] ?? null,
                FirstFactorExponent::NAME => $components['exponents'][1] ?? null,
                SecondFactorExponent::NAME => $components['exponents'][2] ?? null,
                FirstCoefficient::NAME => $components['coefficients'][2] ?? null,
            ],
            $notNull,
        );

        $parameters = array_map([$this, 'fromComponent'], $flattedComponents);

        $exponentExists = array_key_exists(PublicExponent::NAME, $parameters);
        $modulusExists = array_key_exists(Modulus::NAME, $parameters);
        if (!$exponentExists || !$modulusExists) {
            throw new InvalidKeyException('Exponent and/or modulus not found.');
        }

        return array_merge([Type::NAME => Type::RSA], $parameters);
    }

    private function filterRsaParameters(array $parameters): array
    {
        $rsaParameters = array_keys(RsaKeyFactory::DEFAULT_CLASS_MAP);
        $isRsaParameter = static fn (string $name) => in_array($name, $rsaParameters, true);

        return array_filter($parameters, $isRsaParameter, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @inheritDoc
     */
    public function supports(string $type): bool
    {
        return is_a($type, RSA::class, true);
    }
}
