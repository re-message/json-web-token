<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2021 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Standard\Jwt\Handler;

use Exception;
use InvalidArgumentException;
use RM\Standard\Jwt\Exception\ClaimViolationException;
use RM\Standard\Jwt\Exception\InvalidClaimException;
use RM\Standard\Jwt\Exception\InvalidTokenException;
use RM\Standard\Jwt\Token\PropertyBag;
use RM\Standard\Jwt\Token\TokenInterface;

/**
 * Class AbstractClaimHandler
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
abstract class AbstractClaimHandler implements TokenHandlerInterface
{
    public const HEADER_CLAIM  = 'header';
    public const PAYLOAD_CLAIM = 'payload';

    /**
     * Returns name of claim to handle.
     *
     * @return string
     */
    abstract public function getClaim(): string;

    /**
     * Returns the part of the token in which the claim for validation is located
     *
     * @return string
     */
    public function getClaimTarget(): string
    {
        return self::PAYLOAD_CLAIM;
    }

    /**
     * @inheritDoc
     */
    final public function generate(TokenInterface $token): void
    {
        $target = $this->resolveTarget($token);
        if (!$target->containsKey($this->getClaim())) {
            $value = $this->generateValue();
            $target->set($this->getClaim(), $value);
        }
    }

    /**
     * Generate value for current claim.
     *
     * @return mixed
     */
    abstract protected function generateValue();

    /**
     * Checks if the passed value is valid.
     *
     * @param TokenInterface $token
     *
     * @return bool
     * @throws ClaimViolationException
     * @throws InvalidTokenException
     */
    final public function validate(TokenInterface $token): bool
    {
        $target = $this->resolveTarget($token);
        if (!$target->containsKey($this->getClaim())) {
            throw new InvalidTokenException(sprintf('This token does not have claim %s.', $this->getClaim()));
        }

        $value = $target->get($this->getClaim());

        try {
            if ($this->validateValue($value) === true) {
                return true;
            }
        } catch (ClaimViolationException $e) {
            // correct exception, just throw her again
            throw $e;
        } catch (Exception $e) {
            // incorrect exception, throw ClaimViolationException with previous
            throw new ClaimViolationException('The token did not pass validation.', $this, $e);
        }

        // if no exception and result false, then just throw ClaimViolationException
        throw new ClaimViolationException('The token did not pass validation.', $this);
    }

    /**
     * Validate value of this claim.
     * Please throw instance of {@see ClaimViolationException} if this validation failed.
     * If you just return `false` or throw other exception then {@see validate()} will throw {@see ClaimViolationException} self.
     *
     * @param string|int|float|bool $value
     *
     * @return bool
     * @throws ClaimViolationException
     * @throws InvalidClaimException
     */
    abstract protected function validateValue($value): bool;

    /**
     * @param TokenInterface $token
     *
     * @return ParameterBag
     */
    protected function resolveTarget(TokenInterface $token): PropertyBag
    {
        if ($this->getClaimTarget() === self::HEADER_CLAIM) {
            return $token->getHeader();
        }

        if ($this->getClaimTarget() === self::PAYLOAD_CLAIM) {
            return $token->getPayload();
        }

        throw new InvalidArgumentException(
            sprintf(
                'The claim target can be only `header` or `payload`. Got %2$s in %1$s.',
                get_class($this),
                $this->getClaimTarget()
            )
        );
    }
}
