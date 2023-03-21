<?php

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\CheckError;

interface CheckInterface
{
    /**
     * @param mixed $value
     *
     * @return void
     *
     * @throws CheckError if check fails
     */
    public function execute($value): void;

    /**
     * @return bool
     */
    public function isBlocking(): bool;
}
