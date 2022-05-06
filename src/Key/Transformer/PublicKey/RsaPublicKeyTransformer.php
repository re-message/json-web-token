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

namespace RM\Standard\Jwt\Key\Transformer\PublicKey;

use InvalidArgumentException;
use RM\Standard\Jwt\Key\Key;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\KeyOperation;
use RM\Standard\Jwt\Key\Parameter\FirstCoefficient;
use RM\Standard\Jwt\Key\Parameter\FirstFactorExponent;
use RM\Standard\Jwt\Key\Parameter\FirstPrimeFactor;
use RM\Standard\Jwt\Key\Parameter\KeyParameterInterface;
use RM\Standard\Jwt\Key\Parameter\Operations;
use RM\Standard\Jwt\Key\Parameter\PrivateExponent;
use RM\Standard\Jwt\Key\Parameter\SecondFactorExponent;
use RM\Standard\Jwt\Key\Parameter\SecondPrimeFactor;
use RM\Standard\Jwt\Key\Parameter\Type;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class RsaPublicKeyTransformer implements PublicKeyTransformerInterface
{
    public const PRIVATE_PARAMETERS = [
        PrivateExponent::NAME,
        FirstPrimeFactor::NAME,
        SecondPrimeFactor::NAME,
        FirstFactorExponent::NAME,
        SecondFactorExponent::NAME,
        FirstCoefficient::NAME,
    ];

    public function transform(KeyInterface $privateKey): KeyInterface
    {
        $type = $privateKey->getType();
        if (!$this->supports($type)) {
            $message = sprintf(
                '%s does not support key with type "%s".',
                static::class,
                $type,
            );

            throw new InvalidArgumentException($message);
        }

        $parameters = array_filter($privateKey->getParameters(), [$this, 'isPublic']);
        $key = new Key($parameters);

        if ($key->has(Operations::NAME)) {
            $operations = Operations::fromEnum([KeyOperation::VERIFY, KeyOperation::ENCRYPT]);
            $key->set($operations);
        }

        return $key;
    }

    protected function isPublic(KeyParameterInterface $parameter): bool
    {
        $name = $parameter->getName();

        return !in_array($name, self::PRIVATE_PARAMETERS, true);
    }

    public function supports(string $type): bool
    {
        return Type::RSA === $type;
    }
}
