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

namespace RM\Standard\Jwt\Property\Header;

use RM\Standard\Jwt\Algorithm\AlgorithmInterface;
use RM\Standard\Jwt\Token\AbstractProperty;

/**
 * @template-extends AbstractProperty<string>
 * @template-implements HeaderParameterInterface<string>
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
final class Algorithm extends AbstractProperty implements HeaderParameterInterface
{
    public const NAME = 'alg';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function fromAlgorithm(AlgorithmInterface $algorithm): self
    {
        return new Algorithm($algorithm->name());
    }
}
