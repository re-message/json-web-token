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

namespace RM\Standard\Jwt\Key\Factory;

use Override;
use RM\Standard\Jwt\Exception\InvalidKeyException;
use RM\Standard\Jwt\Exception\UnsupportedKeyException;
use RM\Standard\Jwt\Key\Key;
use RM\Standard\Jwt\Key\KeyInterface;
use RM\Standard\Jwt\Key\Parameter\Factory\ParameterFactory;
use RM\Standard\Jwt\Key\Parameter\Factory\ParameterFactoryInterface;
use RM\Standard\Jwt\Key\Parameter\Type;

/**
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
abstract readonly class AbstractKeyFactory implements KeyFactoryInterface
{
    private array $requiredParameters;

    protected function __construct(
        private array $supportedTypes,
        array $requiredParameters = [],
        private ParameterFactoryInterface $parameterFactory = new ParameterFactory(),
    ) {
        $this->requiredParameters = array_merge([Type::NAME], $requiredParameters);
    }

    #[Override]
    public function create(array $content): KeyInterface
    {
        $type = $content[Type::NAME] ?? null;
        if (null === $type || !$this->supports($content)) {
            throw new UnsupportedKeyException($type, static::class);
        }

        if (!$this->hasRequiredParameters($content)) {
            throw new InvalidKeyException('The key does not have some required parameters.');
        }

        return $this->hydrate($content);
    }

    protected function hydrate(array $content): KeyInterface
    {
        $properties = [];
        foreach ($content as $name => $value) {
            $properties[] = $this->parameterFactory->create($name, $value);
        }

        return new Key($properties);
    }

    protected function hasRequiredParameters(array $content): bool
    {
        $parameterNames = array_keys($content);
        $intersection = array_intersect($this->requiredParameters, $parameterNames);

        return count($intersection) === count($this->requiredParameters);
    }

    #[Override]
    public function supports(array $content): bool
    {
        $type = $content[Type::NAME] ?? null;
        if (null === $type) {
            return false;
        }

        return $this->supportsType($type);
    }

    protected function supportsType(string $type): bool
    {
        return in_array($type, $this->supportedTypes, true);
    }
}
