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

namespace RM\Standard\Jwt\Property\Header;

use InvalidArgumentException;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Token\AbstractProperty;

/**
 * @template-extends AbstractProperty<string|int>
 * @template-implements HeaderParameterInterface<string|int>
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class KeyId extends AbstractProperty implements HeaderParameterInterface
{
    public const NAME = 'kid';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function fromKey(KeyInterface $key): self
    {
        if (!$key->has(KeyInterface::PARAM_KEY_IDENTIFIER)) {
            throw new InvalidArgumentException('The key have no identifier.');
        }

        $id = $key->get(KeyInterface::PARAM_KEY_IDENTIFIER);

        return new self($id);
    }
}
