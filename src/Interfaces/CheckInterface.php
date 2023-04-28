<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\CheckError;

interface CheckInterface
{
    /**
     * @param mixed $value
     * @param array<CheckError> $previousErrors
     *
     * @return void
     *
     * @throws CheckError if check fails
     */
    public function execute($value, array $previousErrors): void;

    /**
     * @return bool
     */
    public function isInterrupting(): bool;

    /**
     * @param bool $value
     *
     * @return static
     */
    public function setInterrupting(bool $value = true): self;
}
