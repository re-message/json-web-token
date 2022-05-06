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

namespace RM\Standard\Jwt\Key\Thumbprint;

use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Modulus;
use RM\Standard\Jwt\Key\Parameter\PublicExponent;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Parameter\Value;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 *
 * @see https://datatracker.ietf.org/doc/html/rfc7638
 */
interface ThumbprintFactoryInterface
{
    public const DEFAULT_ALGORITHM = 'sha256';

    // @todo make use parameter constants on implement
    public const THUMBPRINT_PARAMETERS = [
        Type::NAME,
        Value::NAME,
        PublicExponent::NAME,
        Modulus::NAME,
        'crv',
        'x',
        'y',
    ];

    public function create(KeyInterface $key): string;
}
