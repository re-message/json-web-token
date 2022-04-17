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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RM\Standard\Jwt\Exception\AlgorithmNotFoundException;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class AlgorithmManager
{
    /**
     * @var Collection<string, AlgorithmInterface>
     */
    private readonly Collection $algorithms;

    /**
     * @param AlgorithmInterface[] $algorithms
     */
    public function __construct(array $algorithms = [])
    {
        $this->algorithms = new ArrayCollection();

        foreach ($algorithms as $algorithm) {
            $this->put($algorithm);
        }
    }

    public function get(string $algorithm): AlgorithmInterface
    {
        if (!$this->has($algorithm)) {
            throw new AlgorithmNotFoundException($algorithm);
        }

        return $this->algorithms->get($algorithm);
    }

    public function put(AlgorithmInterface $algorithm): void
    {
        $this->algorithms->set($algorithm->name(), $algorithm);
    }

    public function has(string $algorithm): bool
    {
        return $this->algorithms->containsKey($algorithm);
    }

    public function remove(string $algorithm): void
    {
        $this->algorithms->remove($algorithm);
    }
}
