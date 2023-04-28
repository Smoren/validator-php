<?php

declare(strict_types=1);

namespace Smoren\Validator\Structs;

use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;

/**
 * @internal
 */
class RetrospectiveCheck implements CheckInterface
{
    protected const NAME = 'retrospective';

    /**
     * {@inheritDoc}
     *
     * @throws ValidationError if there are previous errors
     */
    public function execute($value, array $previousErrors): void
    {
        if (\count($previousErrors)) {
            throw ValidationError::fromCheckErrors($value, $previousErrors);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return true
     */
    public function isInterrupting(): bool
    {
        return true;
    }

    /**
     * @param bool $value
     *
     * @return static
     */
    public function setInterrupting(bool $value = true): self
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return static::NAME;
    }
}
