<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\CompositeCheckError;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Structs\CheckName;

class AnyOfCheck extends CompositeCheck implements CheckInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute($value, array $previousErrors, bool $preventDuplicate = false): void
    {
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

        throw new CompositeCheckError(CheckName::ANY_OF, $value, $errors);
    }
}