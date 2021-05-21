<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2021 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Token;

use InvalidArgumentException;
use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Algorithm\AlgorithmManager;

/**
 * Class Header
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class Header extends ClaimCollection
{
    /**
     * Algorithm must be set from method { @see AlgorithmInterface::name() } and be in { @see AlgorithmManager }
     */
    public const CLAIM_ALGORITHM = 'alg';

    /**
     * Type of token, by default is `JWT`.
     * If you use some token types please override this claim.
     */
    public const CLAIM_TYPE = 'typ';

    public function __construct(array $parameters = [])
    {
        if (!array_key_exists(self::CLAIM_ALGORITHM, $parameters)) {
            throw new InvalidArgumentException(sprintf('Any JSON Web Token must have the algorithm parameter (`%s`).', self::CLAIM_ALGORITHM));
        }

        parent::__construct(
            $parameters,
            [
                self::CLAIM_ALGORITHM => null,
                self::CLAIM_TYPE => 'JWT'
            ]
        );
    }
}
