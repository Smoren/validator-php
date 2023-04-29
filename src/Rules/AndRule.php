<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\ValidationResultInterface;
use Smoren\Validator\Structs\ValidationSuccessResult;

class AndRule extends CompositeRule
{
    /**
     * {@inheritDoc}
     */
    protected function execute($value): ValidationResultInterface
    {
        $result = parent::execute($value);
        if ($result->preventNextChecks()) {
            return $result;
        }

        $result = new ValidationSuccessResult(false);

        $errors = [];
        foreach ($this->rules as $rule) {
            try {
                $rule->validate($value);
            } catch (ValidationError $e) {
                $errors[] = $e;
                break;
            }
        }

        if (\count($errors) === 0) {
            return $result;
        }

        throw ValidationError::fromValidationErrors($value, $errors);
    }
}
