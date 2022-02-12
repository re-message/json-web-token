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

namespace RM\Standard\Jwt\Signer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Handler\PropertyGeneratorInterface;
use RM\Standard\Jwt\Handler\PropertyTarget;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Token\PropertyBag;
use RM\Standard\Jwt\Token\SignatureToken as Token;

/**
 * Class GeneratedSigner
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class GeneratedSigner extends DecoratedSigner
{
    /**
     * @var Collection<PropertyGeneratorInterface>
     */
    private readonly Collection $generators;

    public function __construct(SignerInterface $signer, array $generators = [])
    {
        parent::__construct($signer);

        $this->generators = new ArrayCollection($generators);
    }

    public function sign(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): Token
    {
        foreach ($this->generators as $generator) {
            $this->generateWith($generator, $token);
        }

        return parent::sign($token, $algorithm, $key);
    }

    public function pushGenerator(PropertyGeneratorInterface $generator): void
    {
        $this->generators->add($generator);
    }

    protected function generateWith(PropertyGeneratorInterface $generator, Token $token): void
    {
        $name = $generator->getPropertyName();
        $target = $generator->getPropertyTarget();

        /** @var PropertyBag $bag */
        $bag = match ($target) {
            PropertyTarget::HEADER => $token->getHeader(),
            PropertyTarget::PAYLOAD => $token->getPayload(),
        };

        if ($bag->has($name)) {
            return;
        }

        $property = $generator->generate();
        $bag->set($property);
    }
}