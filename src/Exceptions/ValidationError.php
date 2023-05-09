<?php

declare(strict_types=1);

namespace Smoren\Validator\Exceptions;

final class ValidationError extends \DomainException
{
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var array<array{string, array<string, mixed>}>
     */
    protected array $violatedRestrictions;

    /**
     * @param string $name
     * @param mixed $value
     * @param array<CheckError> $checkErrors
     *
     * @return self
     */
    public static function fromCheckErrors(string $name, $value, array $checkErrors): self
    {
        /** @var array<array{string, array<string, mixed>}> $violations */
        $violations = [];
        foreach ($checkErrors as $checkError) {
            if ($checkError instanceof CompositeCheckError) {
                foreach ($checkError->getNestedErrors() as $validationError) {
                    $violations = [...$violations, ...$validationError->getViolatedRestrictions()];
                }
            } else {
                $violations[] = [$checkError->getName(), $checkError->getParams()];
            }
        }

        return new self($name, $value, $violations);
    }

    /**
     * @param mixed $value
     * @param array<array{string, array<string, mixed>}> $violatedRestrictions
     */
    public function __construct(string $name, $value, array $violatedRestrictions)
    {
        parent::__construct('Validation error');
        $this->name = $name;
        $this->value = $value;
        $this->violatedRestrictions = $violatedRestrictions;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array<array{string, array<string, mixed>}>
     */
    public function getViolatedRestrictions(): array
    {
        return $this->violatedRestrictions;
    }
}
