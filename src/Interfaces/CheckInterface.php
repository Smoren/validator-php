<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Exceptions\ValidationError;

interface CheckInterface
{
    /**
     * @param mixed $value
     * @param array<CheckError> $previousErrors
     * @param bool $preventDuplicate
     *
     * @return void
     *
     * @throws CheckError if check fails
     */
    public function execute($value, array $previousErrors, bool $preventDuplicate = false): void;
}
