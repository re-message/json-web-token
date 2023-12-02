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

use Override;
use RM\Standard\Jwt\Property\AbstractProperty;

/**
 * Audience is an array of unique identifiers for access recipients of a subject.
 * It is optional claim. May contain the same value as the {@see Subject} claim.
 * This property has no generator or validator.
 *
 * @template-extends AbstractProperty<array<int, string>>
 *
 * @template-implements ClaimInterface<array<int, string>>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class Audience extends AbstractProperty implements ClaimInterface
{
    final public const string NAME = 'aud';

    public function __construct(array $value = [])
    {
        parent::__construct($value);
    }

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
