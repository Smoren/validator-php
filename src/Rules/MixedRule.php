<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\RetrospectiveCheck;
use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\CheckWrapperInterface;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Interfaces\UtilityCheckInterface;
use Smoren\Validator\Interfaces\ValidationResultInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\CheckWrapper;
use Smoren\Validator\Structs\Param;
use Smoren\Validator\Structs\ValidationSuccessResult;

class MixedRule extends BaseRule implements RuleInterface
{
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var array<CheckWrapperInterface>
     */
    protected array $checks = [];
    /**
     * @var bool
     */
    protected bool $isNullable = false;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

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
        return $this->check(
            CheckBuilder::create(CheckName::TRUTHY)
                ->withPredicate(fn ($value) => \boolval($value))
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function falsy(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::FALSY)
                ->withPredicate(fn ($value) => !\boolval($value))
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function equal($value): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::EQUAL)
                ->withPredicate(fn ($actual, $expected) => $actual == $expected)
                ->withParams([Param::EXPECTED => $value])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function same($value): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::SAME)
                ->withPredicate(fn ($actual, $expected) => $actual === $expected)
                ->withParams([Param::EXPECTED => $value])
                ->build()
        );
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
        return $this->check(new RetrospectiveCheck(), true);
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
    public function getName(): string
    {
        return $this->name;
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

            throw new ValidationError($this->name, $value, [[CheckName::NOT_NULL, []]]);
        }

        $errors = [];

        foreach ($this->checks as $check) {
            try {
                $check->getCheck()->execute($value, $errors);
            } catch (CheckError $e) {
                if (!($check->getCheck() instanceof UtilityCheckInterface)) {
                    $errors[] = $e;
                }

                if ($check->isInterrupting()) {
                    throw ValidationError::fromCheckErrors($this->name, $value, $errors);
                }
            }
        }

        if (\count($errors) > 0) {
            throw ValidationError::fromCheckErrors($this->name, $value, $errors);
        }

        return new ValidationSuccessResult(false);
    }
}
