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

namespace RM\Standard\Jwt\Property\Payload;

use DateTimeInterface;
use RM\Standard\Jwt\Property\AbstractProperty;

/**
 * @template-implements ClaimInterface<int>
 * @template-extends AbstractProperty<int>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract class DateValueClaim extends AbstractProperty implements ClaimInterface
{
    public function __construct(int|DateTimeInterface $value)
    {
        if ($value instanceof DateTimeInterface) {
            $value = $value->getTimestamp();
        }

        parent::__construct($value);
    }

    public function setValue(mixed $value): void
    {
        if ($value instanceof DateTimeInterface) {
            parent::setValue($value->getTimestamp());

            return;
        }

        parent::setValue($value);
    }
}
