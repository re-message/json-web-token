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

namespace RM\Standard\Jwt\Algorithm;

use InvalidArgumentException;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class AlgorithmResolver implements AlgorithmResolverInterface
{
    public function __construct(
        private readonly AlgorithmManager $algorithmManager
    ) {}

    public function resolve(TokenInterface $token, string $type): AlgorithmInterface
    {
        $algorithm = $this->algorithmManager->get($token->getAlgorithm());
        if (is_a($algorithm, $type, false)) {
            return $algorithm;
        }

        $message = sprintf(
            'Algorithm must implement %1$s, given %2$s.',
            $algorithm::class,
            $type
        );
        throw new InvalidArgumentException($message);
    }
}
