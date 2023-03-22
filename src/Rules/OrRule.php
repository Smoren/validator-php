<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\ExecutionResultInterface;
use Smoren\Validator\Structs\ExecutionResult;

class OrRule extends CompositeRule
{
    /**
     * {@inheritDoc}
     */
    protected function execute($value): ExecutionResultInterface
    {
        $result = parent::execute($value);
        if ($result->areChecksSufficient()) {
            return $result;
        }

        $result = new ExecutionResult(false);

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
