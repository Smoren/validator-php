<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Exceptions\StopValidationException;
use Smoren\Validator\Exceptions\ValidationError;

class OrRule extends CompositeRule
{
    /**
     * @param $value
     *
     * @return void
     */
    public function validate($value): void
    {
        try {
            parent::validate($value);
        } catch (StopValidationException $e) {
            return;
        }

        $errors = [];
        foreach ($this->rules as $rule) {
            try {
                $rule->validate($value);
                return;
            } catch (ValidationError $e) {
                $errors[] = $e;
            }
        }

        if (\count($errors) === 0) {
            return;
        }

        throw ValidationError::fromValidationErrors($value, $errors);
    }
}
