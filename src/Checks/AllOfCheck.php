<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Structs\RuleName;

class AllOfCheck extends CompositeCheck implements CheckInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws ValidationError
     */
    public function execute($value, array $previousErrors, bool $preventDuplicate = false): void
    {
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
            return;
        }

        throw ValidationError::fromValidationErrors(RuleName::ALL_OF, $value, $errors);
    }
}
