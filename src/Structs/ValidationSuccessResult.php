<?php

declare(strict_types=1);

namespace Smoren\Validator\Structs;

use Smoren\Validator\Interfaces\ValidationResultInterface;

class ValidationSuccessResult implements ValidationResultInterface
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
