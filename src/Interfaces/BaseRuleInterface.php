<?php

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\ValidationError;

interface BaseRuleInterface
{
    /**
     * @param mixed $value
     *
     * @return void
     *
     * @throws ValidationError
     */
    public function validate($value): void;

    /**
     * @return static
     */
    public function nullable(): self;
}
