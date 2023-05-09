<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\CheckError;

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
    public function __invoke($value, array $previousErrors, bool $preventDuplicate = false): void;
}
