<?php

namespace Smoren\Validator\Interfaces;

interface ExecutionResultInterface
{
    /**
     * @return bool
     */
    public function areChecksSufficient(): bool;
}
