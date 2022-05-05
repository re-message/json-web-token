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

namespace RM\Standard\Jwt\Key\Factory;

use RM\Standard\Jwt\Key\Parameter\Exponent;
use RM\Standard\Jwt\Key\Parameter\Factory\ParameterFactory;
use RM\Standard\Jwt\Key\Parameter\Factory\ParameterFactoryInterface;
use RM\Standard\Jwt\Key\Parameter\FirstCoefficient;
use RM\Standard\Jwt\Key\Parameter\FirstFactorExponent;
use RM\Standard\Jwt\Key\Parameter\FirstPrimeFactor;
use RM\Standard\Jwt\Key\Parameter\Modulus;
use RM\Standard\Jwt\Key\Parameter\OtherPrimesInfo;
use RM\Standard\Jwt\Key\Parameter\PrivateExponent;
use RM\Standard\Jwt\Key\Parameter\SecondFactorExponent;
use RM\Standard\Jwt\Key\Parameter\SecondPrimeFactor;
use RM\Standard\Jwt\Key\Parameter\Type;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class RsaKeyFactory extends AbstractKeyFactory
{
    public const DEFAULT_CLASS_MAP = [
        Modulus::NAME => Modulus::class,
        Exponent::NAME => Exponent::class,
        PrivateExponent::NAME => PrivateExponent::class,
        FirstPrimeFactor::NAME => FirstPrimeFactor::class,
        SecondPrimeFactor::NAME => SecondPrimeFactor::class,
        FirstFactorExponent::NAME => FirstFactorExponent::class,
        SecondFactorExponent::NAME => SecondFactorExponent::class,
        FirstCoefficient::NAME => FirstCoefficient::class,
        OtherPrimesInfo::NAME => OtherPrimesInfo::class,
    ];

    public function __construct(
        ParameterFactoryInterface $parameterFactory = new ParameterFactory(self::DEFAULT_CLASS_MAP),
    ) {
        parent::__construct(
            [Type::RSA],
            [Modulus::NAME, Exponent::NAME],
            $parameterFactory,
        );
    }

    public static function createFromMap(array $classMap = []): self
    {
        $classMap = array_merge(self::DEFAULT_CLASS_MAP, $classMap);
        $parameterFactory = new ParameterFactory($classMap);

        return new self($parameterFactory);
    }
}
