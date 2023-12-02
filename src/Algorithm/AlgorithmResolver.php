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

namespace RM\Standard\Jwt\Algorithm;

use InvalidArgumentException;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class AlgorithmResolver implements AlgorithmResolverInterface
{
    public function __construct(
        private readonly AlgorithmManager $algorithmManager
    ) {
    }

    /**
     * @template T of AlgorithmInterface
     */
    public function resolve(TokenInterface $token, string $type): AlgorithmInterface
    {
        $algorithm = $this->algorithmManager->get($token->getAlgorithm());
        if (is_a($algorithm, $type, false)) {
            return $algorithm;
        }

        $message = sprintf(
            'Algorithm must implement %s, given %s.',
            $type,
            $algorithm::class
        );

        throw new InvalidArgumentException($message);
    }
}
