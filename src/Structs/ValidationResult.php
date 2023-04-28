<?php

namespace Smoren\Validator\Structs;

use Smoren\Validator\Interfaces\ValidationResultInterface;

class ValidationResult implements ValidationResultInterface
{
    protected bool $preventNextChecks;

    /**
     * @param bool $preventNextChecks
     */
    public function __construct(bool $preventNextChecks)
    {
        $this->preventNextChecks = $preventNextChecks;
    }

    /**
     * {@inheritDoc}
     */
    public function preventNextChecks(): bool
    {
        return $this->preventNextChecks;
    }
}
