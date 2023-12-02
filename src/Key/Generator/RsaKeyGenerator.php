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

namespace RM\Standard\Jwt\Key\Generator;

use InvalidArgumentException;
use phpseclib3\Crypt\RSA as CryptRSA;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\KeyOperation;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Key\Parameter\Operations;
use RM\Standard\Jwt\Key\Parameter\Type;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactory;
use RM\Standard\Jwt\Key\Thumbprint\ThumbprintFactoryInterface;
use RM\Standard\Jwt\Key\Transformer\SecLib\RsaSecLibTransformer;
use RM\Standard\Jwt\Key\Transformer\SecLib\SecLibTransformerInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class RsaKeyGenerator extends LengthAwareGenerator
{
    public const DEFAULT_LENGTH = 4096;
    public const MIN_LENGTH = 512;

    /**
     * @param SecLibTransformerInterface<CryptRSA> $transformer
     */
    public function __construct(
        private readonly SecLibTransformerInterface $transformer = new RsaSecLibTransformer(),
        private readonly ThumbprintFactoryInterface $thumbprintFactory = new ThumbprintFactory(),
    ) {
        parent::__construct(self::DEFAULT_LENGTH, self::MIN_LENGTH);
    }

    public function generate(string $type, array $options = []): KeyInterface
    {
        if (!$this->supports($type)) {
            $message = sprintf(
                '%s can not generate a key with type "%s".',
                static::class,
                $type,
            );

            throw new InvalidArgumentException($message);
        }

        $length = $this->resolveLength($options);
        $cryptKey = CryptRSA::createKey($length);
        $key = $this->transformer->reverseTransform($cryptKey);

        $thumbprint = $this->thumbprintFactory->create($key);
        $key->set(new Identifier($thumbprint));

        $operations = [KeyOperation::SIGN, KeyOperation::DECRYPT];
        $key->set(Operations::fromEnum($operations));

        return $key;
    }

    public function supports(string $type): bool
    {
        return Type::RSA === $type;
    }
}
