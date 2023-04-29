<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Interfaces\ValidationResultInterface;
use Smoren\Validator\Structs\ValidationSuccessResult;

abstract class BaseRule implements RuleInterface
{
    /**
     * @param mixed $value
     * @return ValidationResultInterface
     */
    protected function execute($value): ValidationResultInterface
    {
        return new ValidationSuccessResult(false);
    }
}
