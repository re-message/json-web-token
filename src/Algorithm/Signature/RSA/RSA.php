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

namespace RM\Standard\Jwt\Algorithm\Signature\RSA;

use phpseclib3\Crypt\RSA as CryptRSA;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Transformer\PhpSecLibTransformer;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract class RSA implements SignatureAlgorithmInterface
{
    public const PADDING_PSS = CryptRSA::SIGNATURE_PSS;

    public const PADDING_PKCS1 = CryptRSA::SIGNATURE_PKCS1;

    public function __construct(
        private readonly PhpSecLibTransformer $transformer = new PhpSecLibTransformer(),
    ) {
    }

    final public function allowedKeyTypes(): array
    {
        return [Type::RSA];
    }

    final public function sign(KeyInterface $key, string $input): string
    {
        return $this->getKey($key, CryptRSA\PrivateKey::class)->sign($input);
    }

    final public function verify(KeyInterface $key, string $input, string $signature): bool
    {
        return $this->getKey($key, CryptRSA\PublicKey::class)->verify($input, $signature);
    }

    /**
     * @template T of AsymmetricKey
     *
     * @param class-string<T> $type
     *
     * @return T
     */
    private function getKey(KeyInterface $key, string $type): CryptRSA
    {
        $rsa = $this->transformer->transform($key, $type);
        $rsa = $rsa->withHash($this->getAlgorithm());
        $rsa = $rsa->withPadding($this->getPadding());

        return $rsa;
    }

    /**
     * Returns name of hash algorithm for RSA signing, like "sha256".
     */
    abstract protected function getAlgorithm(): string;

    /**
     * @see PADDING_PSS
     * @see PADDING_PKCS1
     */
    abstract protected function getPadding(): int;
}
