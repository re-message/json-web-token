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

namespace RM\Standard\Jwt\Generator;

use RM\Standard\Jwt\Identifier\IdentifierGeneratorInterface;
use RM\Standard\Jwt\Identifier\UniqIdGenerator;
use RM\Standard\Jwt\Property\Payload\Identifier;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;
use RM\Standard\Jwt\Token\PropertyInterface;
use RM\Standard\Jwt\Token\PropertyTarget;

/**
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 *
 * @see Identifier
 */
class IdentifierGenerator extends AbstractDurationGenerator
{
    public function __construct(
        protected readonly IdentifierGeneratorInterface $generator = new UniqIdGenerator(),
        protected readonly TokenStorageInterface $storage = new RuntimeTokenStorage(),
        int $duration = self::DEFAULT_DURATION
    ) {
        parent::__construct($duration);
    }

    public function getPropertyName(): string
    {
        return Identifier::NAME;
    }

    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    public function generate(): PropertyInterface
    {
        $identifier = $this->generator->generate();
        $this->storage->put($identifier, $this->getDuration());

        return new Identifier($identifier);
    }
}
