<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Interfaces\ExecutionResultInterface;
use Smoren\Validator\Structs\Check;
use Smoren\Validator\Structs\ExecutionResult;
use Smoren\Validator\Structs\RetrospectiveCheck;

class Rule extends BaseRule implements RuleInterface
{
    public const ERROR_NULL = 'null';
    public const ERROR_NOT_TRUTHY = 'not_truthy';
    public const ERROR_NOT_FALSY = 'not_falsy';

    /**
     * @var array<CheckInterface>
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
        return $this->addCheck(new Check(
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_FALSY,
            fn ($value) => !boolval($value),
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function addCheck(CheckInterface $check): self
    {
        $this->checks[] = $check;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function check(string $name, callable $predicate, array $params = [], bool $isInterrupting = false): self
    {
        return $this->addCheck(new Check($name, $predicate, $params, $isInterrupting));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stopOnViolation(): self
    {
        return $this->addCheck(new RetrospectiveCheck());
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
    protected function execute($value): ExecutionResultInterface
    {
        $result = parent::execute($value);
        if ($result->areChecksSufficient()) {
            return $result;
        }

        if ($value === null) {
            if ($this->isNullable) {
                return new ExecutionResult(true);
            }

            throw new ValidationError($value, [[self::ERROR_NULL, []]]);
        }

        $errors = [];

        foreach ($this->checks as $check) {
            try {
                $check->execute($value, $errors);
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

        return new ExecutionResult(false);
    }
}
