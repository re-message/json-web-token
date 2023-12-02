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

namespace RM\Standard\Jwt\Property\Header;

use InvalidArgumentException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Identifier;
use RM\Standard\Jwt\Property\AbstractProperty;

/**
 * @template-extends AbstractProperty<int|string>
 *
 * @template-implements HeaderParameterInterface<int|string>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class KeyId extends AbstractProperty implements HeaderParameterInterface
{
    public const NAME = 'kid';

    public function __construct(int|string $value)
    {
        parent::__construct($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public static function fromKey(KeyInterface $key): self
    {
        if (!$key->has(Identifier::NAME)) {
            throw new InvalidArgumentException('The key have no identifier.');
        }

        $id = $key->get(Identifier::NAME)->getValue();

        return new self($id);
    }
}
