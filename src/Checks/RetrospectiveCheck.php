<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Interfaces\UtilityCheckInterface;

/**
 * @internal
 */
class RetrospectiveCheck implements UtilityCheckInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute($value, array $previousErrors): void
    {
        if (\count($previousErrors)) {
            throw new CheckError('retrospective', $value, $previousErrors);
        }
    }
}
