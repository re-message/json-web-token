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

use RM\Standard\Jwt\Property\AbstractProperty;

/**
 * @template-extends AbstractProperty<mixed>
 * @template-implements HeaderParameterInterface<mixed>
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class Custom extends AbstractProperty implements HeaderParameterInterface
{
    private string $name;

    public function __construct(string $name, mixed $value = null)
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
