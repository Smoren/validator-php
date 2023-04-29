<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;

/**
 * @internal
 */
class RetrospectiveCheck implements CheckInterface
{
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
     */
    public function getName(): ?string
    {
        return null;
    }
}
