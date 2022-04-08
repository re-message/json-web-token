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

namespace RM\Standard\Jwt\Signature;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface as AlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Signature\SignatureToken as Token;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class LoggableSigner extends DecoratedSigner
{
    private LoggerInterface $logger;

    public function __construct(SignerInterface $signer, LoggerInterface $logger = null)
    {
        parent::__construct($signer);

        $this->logger = $logger ?? new NullLogger();
    }

    public function sign(Token $token, AlgorithmInterface $algorithm, KeyInterface $key): Token
    {
        $signedToken = parent::sign($token, $algorithm, $key);

        $this->logger->debug(
            'Token signed by hash algorithm signature',
            ['algorithm' => $algorithm->name()]
        );

        return $signedToken;
    }
}
