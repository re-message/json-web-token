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

namespace RM\Standard\Jwt\Signature;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Generator\PropertyGeneratorInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Signature\SignatureToken as Token;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class GeneratedSigner extends DecoratedSigner
{
    /**
     * @var Collection<int, PropertyGeneratorInterface>
     */
    private readonly Collection $generators;

    /**
     * @param iterable<PropertyGeneratorInterface> $generators
     */
    public function __construct(SignerInterface $signer, iterable $generators = [])
    {
        parent::__construct($signer);

        $this->generators = new ArrayCollection();

        foreach ($generators as $generator) {
            $this->pushGenerator($generator);
        }
    }

    public function sign(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): Token
    {
        // detach token to avoid the value changes in original token
        $target = clone $token;

        foreach ($this->generators as $generator) {
            $this->generateWith($generator, $target);
        }

        return parent::sign($target, $algorithm, $key);
    }

    public function pushGenerator(PropertyGeneratorInterface $generator): void
    {
        $this->generators->add($generator);
    }

    protected function generateWith(PropertyGeneratorInterface $generator, Token $token): void
    {
        $name = $generator->getPropertyName();
        $target = $generator->getPropertyTarget();

        $bag = $target->resolve($token);
        if ($bag->has($name)) {
            return;
        }

        $property = $generator->generate();
        $bag->set($property);
    }
}
