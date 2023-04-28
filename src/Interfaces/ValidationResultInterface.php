<?php

namespace Smoren\Validator\Interfaces;

interface ValidationResultInterface
{
    /**
     * @return bool
     */
    public function preventNextChecks(): bool;
}
