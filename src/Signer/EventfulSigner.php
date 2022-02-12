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

use Psr\EventDispatcher\EventDispatcherInterface;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Event\TokenPreSignEvent;
use RM\Standard\Jwt\Event\TokenSignEvent;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Token\SignatureToken as Token;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class EventfulSigner extends DecoratedSigner
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(SignerInterface $signer, EventDispatcherInterface $eventDispatcher = null)
    {
        parent::__construct($signer);

        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();
    }

    public function sign(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): Token
    {
        $this->eventDispatcher->dispatch(new TokenPreSignEvent($token));

        $signedToken = parent::sign($token, $algorithm, $key);

        $signEvent = new TokenSignEvent($signedToken);
        $this->eventDispatcher->dispatch($signEvent);

        return $signEvent->getToken();
    }
}
