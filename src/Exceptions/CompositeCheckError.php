<?php

declare(strict_types=1);

namespace Smoren\Validator\Exceptions;

final class CompositeCheckError extends CheckError
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

    /**
     * @param CheckError $error
     *
     * @return bool
     */
    public function equalTo(CheckError $error): bool
    {
        return $error instanceof CompositeCheckError
            && parent::equalTo($error)
            && $this->hasSameNestedErrorsWith($error);
    }

    /**
     * @param CompositeCheckError $error
     *
     * @return bool
     */
    protected function hasSameNestedErrorsWith(CompositeCheckError $error): bool
    {
        return (
            array_map(fn ($e) => $e->getViolatedRestrictions(), $this->nestedErrors) ===
            array_map(fn ($e) => $e->getViolatedRestrictions(), $error->nestedErrors)
        );
    }
}
