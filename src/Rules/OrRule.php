<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\ValidationResultInterface;
use Smoren\Validator\Structs\ValidationResult;

class OrRule extends CompositeRule
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

        $result = new ValidationResult(false);

        $errors = [];
        foreach ($this->rules as $rule) {
            try {
                $rule->validate($value);
                return $result;
            } catch (ValidationError $e) {
                $errors[] = $e;
            }
        }

        if (\count($errors) === 0) {
            return $result;
        }

        throw ValidationError::fromValidationErrors($value, $errors);
    }
}
