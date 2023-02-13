<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-ext/jwt
 *
 * @link     https://github.com/hyperf-ext/jwt
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/jwt/blob/master/LICENSE
 */
namespace HyperfExt\Jwt\Claims;

use HyperfExt\Jwt\Exceptions\InvalidClaimException;
use HyperfExt\Jwt\Utils;

trait DatetimeTrait
{
    /**
     * Time leeway in seconds.
     */
    protected int $leeway = 0;

    /**
     * Set the claim value, and call a validate method.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue(mixed $value): static
    {
        if ($value instanceof \DateInterval) {
            $value = Utils::now()->add($value);
        }

        if ($value instanceof \DateTimeInterface) {
            $value = $value->getTimestamp();
        }

        return parent::setValue($value);
    }

    /**
     * {@inheritdoc}
     * @throws InvalidClaimException
     */
    public function validateCreate(mixed $value): float|int|string
    {
        if (! is_numeric($value)) {
            throw new InvalidClaimException($this);
        }

        return $value;
    }

    /**
     * Set the leeway in seconds.
     *
     * @return $this
     */
    public function setLeeway(int $leeway): static
    {
        $this->leeway = $leeway;

        return $this;
    }

    /**
     * Determine whether the value is in the future.
     */
    protected function isFuture(mixed $value): bool
    {
        return Utils::isFuture((int) $value, $this->leeway);
    }

    /**
     * Determine whether the value is in the past.
     */
    protected function isPast(mixed $value): bool
    {
        return Utils::isPast((int) $value, $this->leeway);
    }
}
