<?php

declare(strict_types=1);

namespace Smoren\Validator\Exceptions;

class CompositeCheckError extends CheckError
{
    /**
     * @var array<ValidationError>
     */
    protected array $nestedErrors;

    /**
     * @param string $name
     * @param mixed $value
     * @param array<ValidationError> $errors
     */
    public function __construct(string $name, $value, array $errors)
    {
        parent::__construct($name, $value, []);
        $this->nestedErrors = $errors;
    }

    /**
     * @return array<ValidationError>
     */
    public function getNestedErrors(): array
    {
        return $this->nestedErrors;
    }
}
