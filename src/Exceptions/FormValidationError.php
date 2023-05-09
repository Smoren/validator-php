<?php

declare(strict_types=1);

namespace Smoren\Validator\Exceptions;

final class FormValidationError extends \DomainException
{
    /**
     * @var class-string
     */
    protected string $formClass;
    /**
     * @var array<string, ValidationError>
     */
    protected array $attributeErrorMap;

    /**
     * @param class-string $formClass
     * @param array<string, ValidationError> $attributeErrorMap
     */
    public function __construct(string $formClass, array $attributeErrorMap)
    {
        parent::__construct('Validation error');
        $this->formClass = $formClass;
        $this->attributeErrorMap = $attributeErrorMap;
    }

    /**
     * @return class-string
     */
    public function getFormClass(): string
    {
        return $this->formClass;
    }

    /**
     * @return array<string, array<array{string, array<string, mixed>}>>
     */
    public function getViolatedRestrictions(): array
    {
        $result = [];
        foreach ($this->attributeErrorMap as $attrName => $error) {
            $result[$attrName] = $error->getViolatedRestrictions();
        }
        return $result;
    }
}
