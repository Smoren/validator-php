<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Interfaces\UtilityCheckInterface;

/**
 * @internal
 */
final class RetrospectiveCheck implements UtilityCheckInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke($value, array $previousErrors, bool $preventDuplicate = false): void
    {
        if (\count($previousErrors)) {
            throw new CheckError('retrospective', $value, $previousErrors);
        }
    }
}
