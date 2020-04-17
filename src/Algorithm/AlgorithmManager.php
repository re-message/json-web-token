<?php
/**
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Algorithm;

use RM\Standard\Jwt\Exception\AlgorithmNotFoundException;

/**
 * Class AlgorithmManager
 *
 * @package RM\Standard\Jwt\Algorithm
 * @author  h1karo <h1karo@outlook.com>
 */
class AlgorithmManager
{
    /**
     * @var AlgorithmInterface[]
     */
    private array $algorithms = [];

    /**
     * AlgorithmManager constructor.
     *
     * @param AlgorithmInterface[] $algorithms
     */
    public function __construct(array $algorithms = [])
    {
        foreach ($algorithms as $algorithm) {
            $this->put($algorithm);
        }
    }

    /**
     * Returns any algorithm by name
     *
     * @param string $algorithm
     *
     * @return AlgorithmInterface
     * @throws AlgorithmNotFoundException
     */
    public function get(string $algorithm): AlgorithmInterface
    {
        if (!$this->has($algorithm)) {
            throw new AlgorithmNotFoundException($algorithm);
        }

        return $this->algorithms[$algorithm];
    }

    /**
     * @param AlgorithmInterface $algorithm
     */
    public function put(AlgorithmInterface $algorithm): void
    {
        $this->algorithms[$algorithm->name()] = $algorithm;
    }

    /**
     * @param string $algorithm
     *
     * @return bool
     */
    public function has(string $algorithm): bool
    {
        return array_key_exists($algorithm, $this->algorithms);
    }

    /**
     * @param string $algorithm
     */
    public function remove(string $algorithm): void
    {
        unset($this->algorithms[$algorithm]);
    }
}