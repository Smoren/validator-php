<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface ValidationResultInterface
{
    /**
     * @return bool
     */
    public function preventNextChecks(): bool;
}
