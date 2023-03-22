<?php

namespace Smoren\Validator\Structs;

use Smoren\Validator\Interfaces\ExecutionResultInterface;

class ExecutionResult implements ExecutionResultInterface
{
    protected bool $areChecksSufficient;

    /**
     * @param bool $areChecksSufficient
     */
    public function __construct(bool $areChecksSufficient)
    {
        $this->areChecksSufficient = $areChecksSufficient;
    }

    /**
     * {@inheritDoc}
     */
    public function areChecksSufficient(): bool
    {
        return $this->areChecksSufficient;
    }
}
