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

namespace RM\Standard\Jwt\Generator;

use Override;
use RM\Standard\Jwt\Identifier\IdentifierGeneratorInterface;
use RM\Standard\Jwt\Identifier\UniqIdGenerator;
use RM\Standard\Jwt\Property\Payload\Identifier;
use RM\Standard\Jwt\Property\PropertyInterface;
use RM\Standard\Jwt\Property\PropertyTarget;
use RM\Standard\Jwt\Storage\RuntimeTokenStorage;
use RM\Standard\Jwt\Storage\TokenStorageInterface;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
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

    #[Override]
    public function getPropertyName(): string
    {
        return Identifier::NAME;
    }

    #[Override]
    public function getPropertyTarget(): PropertyTarget
    {
        return PropertyTarget::PAYLOAD;
    }

    #[Override]
    public function generate(): PropertyInterface
    {
        $identifier = $this->generator->generate();
        $this->storage->put($identifier, $this->getDuration());

        return new Identifier($identifier);
    }
}
