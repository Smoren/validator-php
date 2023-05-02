<?php

declare(strict_types=1);

namespace Smoren\Validator\Exceptions;

class ValidationError extends \DomainException
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
        return new self($name, $value, array_map(
            fn (CheckError $error) => [$error->getName(), $error->getParams()],
            $checkErrors
        ));
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param array<ValidationError> $validationErrors
     *
     * @return self
     */
    public static function fromValidationErrors(string $name, $value, array $validationErrors): self
    {
        $summary = [];

        foreach ($validationErrors as $error) {
            foreach ($error->getViolatedRestrictions() as $item) {
                $summary[] = $item;
            }
        }

        return new self($name, $value, $summary);
    }

    /**
     * @param mixed $value
     * @param array<array{string, array<string, mixed>}> $summary
     */
    public function __construct(string $name, $value, array $summary)
    {
        parent::__construct('Validation error');
        $this->name = $name;
        $this->value = $value;
        $this->violatedRestrictions = $summary;
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
