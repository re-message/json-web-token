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

namespace RM\Standard\Jwt\Algorithm\Signature;

use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Type;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class None implements SignatureAlgorithmInterface
{
    public function name(): string
    {
        return 'none';
    }

    public function allowedKeyTypes(): array
    {
        return [Type::NONE];
    }

    public function hash(KeyInterface $key, string $input): string
    {
        return '';
    }

    public function verify(KeyInterface $key, string $input, string $hash): bool
    {
        return $this->hash($key, $input) === $hash;
    }
}
