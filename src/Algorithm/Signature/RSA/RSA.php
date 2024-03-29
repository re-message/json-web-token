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

namespace RM\Standard\Jwt\Algorithm\Signature\RSA;

use Override;
use phpseclib3\Crypt\RSA as CryptRSA;
use RM\Standard\Jwt\Algorithm\Signature\SignatureAlgorithmInterface;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Transformer\PublicKey\PublicKeyTransformerInterface;
use RM\Standard\Jwt\Key\Transformer\PublicKey\RsaPublicKeyTransformer;
use RM\Standard\Jwt\Key\Transformer\SecLib\RsaSecLibTransformer;
use RM\Standard\Jwt\Key\Transformer\SecLib\SecLibTransformerInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract readonly class RSA implements SignatureAlgorithmInterface
{
    final public const int PADDING_PSS = CryptRSA::SIGNATURE_PSS;

    final public const int PADDING_PKCS1 = CryptRSA::SIGNATURE_PKCS1;

    /**
     * @param SecLibTransformerInterface<CryptRSA> $transformer
     */
    public function __construct(
        private SecLibTransformerInterface $transformer = new RsaSecLibTransformer(),
        private PublicKeyTransformerInterface $publicKeyTransformer = new RsaPublicKeyTransformer()
    ) {}

    #[Override]
    final public function allowedKeyTypes(): array
    {
        return [Type::RSA];
    }

    #[Override]
    final public function sign(KeyInterface $key, string $input): string
    {
        return $this->getKey($key, CryptRSA\PrivateKey::class)->sign($input);
    }

    #[Override]
    final public function verify(KeyInterface $key, string $input, string $signature): bool
    {
        $publicKey = $this->publicKeyTransformer->transform($key);

        return $this->getKey($publicKey, CryptRSA\PublicKey::class)->verify($input, $signature);
    }

    /**
     * @template T of CryptRSA
     *
     * @param class-string<T> $type
     *
     * @return T
     */
    private function getKey(KeyInterface $key, string $type): CryptRSA
    {
        return $this->transformer->transform($key, $type)
            ->withHash($this->getAlgorithm())
            ->withPadding($this->getPadding())
        ;
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
