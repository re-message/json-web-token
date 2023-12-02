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

namespace RM\Standard\Jwt\Property;

use BadMethodCallException;
use RM\Standard\Jwt\Property\Header\HeaderParameterInterface;
use RM\Standard\Jwt\Property\Payload\ClaimInterface;
use RM\Standard\Jwt\Signature\SignatureToken;
use RM\Standard\Jwt\Token\TokenInterface;
use UnexpectedValueException;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
enum PropertyTarget
{
    case HEADER;

    case PAYLOAD;

    public static function getByProperty(PropertyInterface $property): self
    {
        if ($property instanceof HeaderParameterInterface) {
            return self::HEADER;
        }

        if ($property instanceof ClaimInterface) {
            return self::PAYLOAD;
        }

        throw new UnexpectedValueException('Unable to find target by this property');
    }

    public function getBag(TokenInterface $token): PropertyBagInterface
    {
        if (!$token instanceof SignatureToken) {
            throw new BadMethodCallException('Not implemented yet.');
        }

        return match ($this) {
            self::HEADER => $token->getHeader(),
            self::PAYLOAD => $token->getPayload(),
        };
    }
}
