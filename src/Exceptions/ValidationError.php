<?php

namespace Smoren\Validator\Exceptions;

class ValidationError extends \DomainException
{
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var array<array{string, array<string, mixed>}>
     */
    protected array $summary;

    /**
     * @param mixed $value
     * @param array<CheckError> $checkErrors
     * @return self
     */
    public static function fromCheckErrors($value, array $checkErrors): self
    {
        return new self($value, array_map(
            fn (CheckError $error) => [$error->getName(), $error->getParams()],
            $checkErrors
        ));
    }

    /**
     * @param mixed $value
     * @param array<array{string, array<string, mixed>}> $summary
     */
    public function __construct($value, array $summary)
    {
        parent::__construct('Validation error');
        $this->value = $value;
        $this->summary = $summary;
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
    public function getSummary(): array
    {
        return $this->summary;
    }
}
