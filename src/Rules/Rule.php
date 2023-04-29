<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Checks\RetrospectiveCheck;
use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\CheckWrapperInterface;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Interfaces\ValidationResultInterface;
use Smoren\Validator\Structs\CheckWrapper;
use Smoren\Validator\Structs\ValidationSuccessResult;

class Rule extends BaseRule implements RuleInterface
{
    public const ERROR_NULL = 'null';
    public const ERROR_NOT_TRUTHY = 'not_truthy';
    public const ERROR_NOT_FALSY = 'not_falsy';
    public const ERROR_NOT_EQUEAL = 'equal';
    public const ERROR_NOT_SAME = 'same';

    /**
     * @var array<CheckWrapperInterface>
     */
    protected array $checks = [];
    /**
     * @var bool
     */
    protected bool $isNullable = false;

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nullable(): self
    {
        $this->isNullable = true;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function truthy(): self
    {
        return $this->check(new Check(
            self::ERROR_NOT_TRUTHY,
            fn ($value) => boolval($value),
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function falsy(): self
    {
        return $this->check(new Check(
            self::ERROR_NOT_FALSY,
            fn ($value) => !boolval($value),
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function equal($values): self
    {
        return $this->check(new Check(
            self::ERROR_NOT_EQUEAL,
            fn ($value) => $value == $values,
            ['number' => $values]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function same($value): self
    {
        return $this->check(new Check(
            self::ERROR_NOT_SAME,
            fn ($value) => $value === $value,
            ['number' => $value]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function check(CheckInterface $check, bool $isInterrupting = false): self
    {
        $this->checks[] = new CheckWrapper($check, $isInterrupting);
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stopOnViolation(): self
    {
        return $this->check(new RetrospectiveCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stopOnAnyPriorViolation(): self
    {
        foreach ($this->checks as $check) {
            $check->setInterrupting();
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value): void
    {
        $this->execute($value);
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($value): bool
    {
        try {
            $this->validate($value);
            return true;
        } catch (ValidationError $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function execute($value): ValidationResultInterface
    {
        $result = parent::execute($value);
        if ($result->preventNextChecks()) {
            return $result;
        }

        if ($value === null) {
            if ($this->isNullable) {
                return new ValidationSuccessResult(true);
            }

            throw new ValidationError($value, [[self::ERROR_NULL, []]]);
        }

        $errors = [];

        foreach ($this->checks as $check) {
            try {
                $check->getCheck()->execute($value, $errors);
            } catch (CheckError $e) {
                $errors[] = $e;
                if ($check->isInterrupting()) {
                    throw ValidationError::fromCheckErrors($value, $errors);
                }
            }
        }

        if (\count($errors) > 0) {
            throw ValidationError::fromCheckErrors($value, $errors);
        }

        return new ValidationSuccessResult(false);
    }
}
