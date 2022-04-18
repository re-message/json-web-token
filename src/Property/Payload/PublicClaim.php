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

namespace RM\Standard\Jwt\Property\Payload;

use RM\Standard\Jwt\Token\AbstractProperty;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @template-extends AbstractProperty<mixed>
 * @template-implements ClaimInterface<mixed>
 */
class PublicClaim extends AbstractProperty implements ClaimInterface
{
    private string $name;

    public function __construct(string $name, $value = null)
    {
        parent::__construct($value);

        $this->setName($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
