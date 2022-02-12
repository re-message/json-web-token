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

namespace RM\Standard\Jwt\Handler;

use RM\Standard\Jwt\Generator\IdentifierGenerator;
use RM\Standard\Jwt\Identifier\IdentifierGeneratorInterface;
use RM\Standard\Jwt\Property\Payload\Identifier;
use RM\Standard\Jwt\Storage\TokenStorageInterface;
use RM\Standard\Jwt\Validator\Property\IdentifierValidator;

/**
 * Class IdentifierClaimHandler provides processing for { @see Identifier } claim.
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class IdentifierClaimHandler extends DelegatingPropertyHandler
{
    public function __construct(
        IdentifierGeneratorInterface $identifierGenerator = null,
        TokenStorageInterface $storage = null,
        int $duration = 60 * 60
    ) {
        $generator = new IdentifierGenerator($identifierGenerator, $storage, $duration);
        $validator = new IdentifierValidator($storage);

        parent::__construct($generator, $validator);
    }
}
