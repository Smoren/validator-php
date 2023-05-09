<?php

declare(strict_types=1);

namespace Smoren\Validator\Models;

use Smoren\Validator\Exceptions\FormInvalidConfigError;
use Smoren\Validator\Exceptions\FormValidationError;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\FormInterface;
use Smoren\Validator\Interfaces\MixedRuleInterface;

abstract class Form implements FormInterface
{
    /**
     * @var array<string>
     */
    protected array $allowedAttributes = [];
    /**
     * @var array<string, MixedRuleInterface>
     */
    protected array $attributeRules = [];

    /**
     * {@inheritDoc}
     */
    public static function create(array $source): FormInterface
    {
        return new static($source);
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): void
    {
        $errorMap = [];
        foreach ($this->getAttributes() as $attrName => $attrValue) {
            try {
                $this->attributeRules[$attrName]->validate($attrValue);
            } catch (ValidationError $e) {
                $errorMap[$attrName] = $e;
            }
        }
        if (\count($errorMap) > 0) {
            throw new FormValidationError(static::class, $errorMap);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes(): array
    {
        $result = [];
        foreach ($this->allowedAttributes as $attrName) {
            $result[$attrName] = $this->{$attrName};
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function setAttributes(array $source): void
    {
        foreach ($this->allowedAttributes as $attrName) {
            if (\array_key_exists($attrName, $source)) {
                $this->{$attrName} = $source[$attrName];
            }
        }
    }

    /**
     * @return array<array{array<string>, MixedRuleInterface}>
     */
    abstract protected function getRules(): array;

    /**
     * @return void
     */
    protected function registerAttributes(): void
    {
        $reflector = new \ReflectionClass($this);
        foreach ($reflector->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $attrName = $prop->getName();
            $this->allowedAttributes[] = $attrName;
        }
    }

    /**
     * @return void
     */
    protected function registerRules(): void
    {
        /** @var array<string, array<MixedRuleInterface>> $attributeRulesMap */
        $attributeRulesMap = [];
        foreach ($this->allowedAttributes as $attrName) {
            $attributeRulesMap[$attrName] = [];
        }

        foreach ($this->getRules() as [$attrNames, $rule]) {
            if (!($rule instanceof MixedRuleInterface)) {
                $className = MixedRuleInterface::class;
                throw new FormInvalidConfigError(
                    "Some rule is not an instance of '{$className}'",
                    static::class
                );
            }

            foreach ($attrNames as $attrName) {
                if (!\array_key_exists($attrName, $attributeRulesMap)) {
                    throw new FormInvalidConfigError(
                        "Attribute '{$attrName}' is not allowed in form",
                        static::class
                    );
                }
                $attributeRulesMap[$attrName][] = $rule;
            }
        }

        foreach ($attributeRulesMap as $attrName => $rules) {
            if (\count($rules) === 1) {
                $this->attributeRules[$attrName] = $rules[0];
            } elseif (\count($rules) > 1) {
                $this->attributeRules[$attrName] = Value::allOf($rules);
            } else {
                throw new FormInvalidConfigError(
                    "No rules specified for attribute '{$attrName}'",
                    static::class
                );
            }
        }
    }

    /**
     * @param array<string, mixed> $source
     */
    final protected function __construct(array $source)
    {
        $this->registerAttributes();
        $this->registerRules();
        $this->setAttributes($source);
    }
}
