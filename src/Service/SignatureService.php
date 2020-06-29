<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Service;

use InvalidArgumentException;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RM\Standard\Jwt\Algorithm\AlgorithmManager;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Event\TokenPreSignEvent;
use RM\Standard\Jwt\Event\TokenSignEvent;
use RM\Standard\Jwt\Exception\AlgorithmNotFoundException;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Handler\TokenHandlerList;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Serializer\SignatureCompactSerializer;
use RM\Standard\Jwt\Serializer\SignatureSerializerInterface;
use RM\Standard\Jwt\Token\SignatureToken;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class SignatureService
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class SignatureService implements SignatureServiceInterface
{
    private AlgorithmManager $algorithmManager;
    private ?TokenHandlerList $handlerList;
    private SignatureSerializerInterface $serializer;
    private EventDispatcherInterface $eventDispatcher;
    private LoggerInterface $logger;

    public function __construct(
        AlgorithmManager $algorithmManager,
        TokenHandlerList $handlerList = null,
        SignatureSerializerInterface $serializer = null,
        EventDispatcherInterface $eventDispatcher = null,
        LoggerInterface $logger = null
    ) {
        $this->algorithmManager = $algorithmManager;
        $this->handlerList = $handlerList ?? new TokenHandlerList();
        $this->serializer = $serializer ?? new SignatureCompactSerializer();
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @inheritDoc
     */
    final public function sign(SignatureToken $originalToken, KeyInterface $key, bool $resign = false): SignatureToken
    {
        if (!$resign && $originalToken->isSigned()) {
            throw new InvalidTokenException(
                'This token already signed. If you wants to resign them, set `resign` argument on `true`.'
            );
        }

        $this->logger->info('Token sign started.', ['service' => get_class($this), 'token' => $originalToken]);

        $algorithm = $this->findAlgorithm($originalToken->getAlgorithm());
        $this->logger->debug('Found a algorithm to sign.', ['algorithm' => $algorithm->name()]);

        $this->eventDispatcher->dispatch(new TokenPreSignEvent($originalToken));

        // detach token to avoid the claims value changes in original token
        $token = clone $originalToken;

        $this->handlerList->generate($token);
        $this->logger->debug('Handlers processed the token.');

        $signature = $algorithm->hash($key, $this->serializer->serialize($token, true));
        $signedToken = $token->setSignature($signature);

        $this->logger->debug(
            'Signature generated with hash algorithm.',
            [
                'signature' => Base64UrlSafe::encode($signature),
                'algorithm' => $algorithm->name()
            ]
        );

        $this->eventDispatcher->dispatch(new TokenSignEvent($signedToken));
        $this->logger->info('Token sign complete successful.', ['token' => $signedToken]);

        return $signedToken;
    }

    /**
     * @inheritDoc
     */
    final public function verify(SignatureToken $token, KeyInterface $key): bool
    {
        if (!$token->isSigned()) {
            return false;
        }

        if (!$this->handlerList->validate($token)) {
            return false;
        }

        $resignedToken = $this->sign($token, $key, true);
        return hash_equals($token->getSignature(), $resignedToken->getSignature());
    }

    /**
     * Returns signature algorithm by name from manager
     *
     * @param string $name
     *
     * @return SignatureAlgorithmInterface
     * @throws AlgorithmNotFoundException
     */
    public function findAlgorithm(string $name): SignatureAlgorithmInterface
    {
        $algorithm = $this->algorithmManager->get($name);

        if (!$algorithm instanceof SignatureAlgorithmInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Signature algorithm must implement %1$s, given %2$s.',
                    SignatureAlgorithmInterface::class,
                    get_class($algorithm)
                )
            );
        }

        return $algorithm;
    }

    public function getAlgorithmManager(): AlgorithmManager
    {
        return $this->algorithmManager;
    }
}
