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
 * Subject is a unique identity of object token provides access to.
 * It is optional claim.
 *
 * @template-extends AbstractProperty<string>
 *
 * @template-implements ClaimInterface<string>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class Subject extends AbstractProperty implements ClaimInterface
{
    final public const string NAME = 'sub';

    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    #[Override]
    public function getName(): string
    {
        return self::NAME;
    }
}
