<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\NumericRuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class NumericRule extends Rule implements NumericRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->check(
            CheckBuilder::create(CheckName::NUMERIC, CheckErrorName::NOT_NUMERIC)
                ->withPredicate(fn ($value) => \is_numeric($value))
                ->build(),
            true
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function number(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NUMBER, CheckErrorName::NOT_NUMBER)
                ->withPredicate(fn ($value) => \is_int($value) || \is_float($value))
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function string(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::STRING, CheckErrorName::NOT_STRING)
                ->withPredicate(fn ($value) => \is_string($value))
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function truthy(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::TRUTHY, CheckErrorName::NOT_TRUTHY)
                ->withPredicate(fn ($value) => \boolval(floatval($value)))
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
            CheckBuilder::create(CheckName::FALSY, CheckErrorName::NOT_FALSY)
                ->withPredicate(fn ($value) => !\boolval(floatval($value)))
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function positive(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::POSITIVE, CheckErrorName::NOT_POSITIVE)
                ->withPredicate(fn ($value) => $value > 0)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonPositive(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NON_POSITIVE, CheckErrorName::NOT_NON_POSITIVE)
                ->withPredicate(fn ($value) => $value <= 0)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonNegative(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NON_NEGATIVE, CheckErrorName::NOT_NON_NEGATIVE)
                ->withPredicate(fn ($value) => $value >= 0)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function negative(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NEGATIVE, CheckErrorName::NOT_NEGATIVE)
                ->withPredicate(fn ($value) => $value < 0)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function greaterTran($number): NumericRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::GREATER, CheckErrorName::NOT_GREATER)
                ->withPredicate(fn ($value, $number) => $value > $number)
                ->withParams([Param::EXPECTED => $number])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function greaterOrEqual($number): NumericRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::GREATER_OR_EQUEAL, CheckErrorName::NOT_GREATER_OR_EQUEAL)
                ->withPredicate(fn ($value, $number) => $value >= $number)
                ->withParams([Param::EXPECTED => $number])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lessTran($number): NumericRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::LESS, CheckErrorName::NOT_LESS)
                ->withPredicate(fn ($value, $number) => $value < $number)
                ->withParams([Param::EXPECTED => $number])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lessOrEqual($number): NumericRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::LESS_OR_EQUEAL, CheckErrorName::NOT_LESS_OR_EQUEAL)
                ->withPredicate(fn ($value, $number) => $value <= $number)
                ->withParams([Param::EXPECTED => $number])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function between($start, $end): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::BETWEEN, CheckErrorName::NOT_BETWEEN)
                ->withPredicate(fn ($value, $start, $end) => $value >= $start && $value <= $end)
                ->withParams(['start' => $start, 'end' => $end])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function inInterval($start, $end): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::IN_INTERVAL, CheckErrorName::NOT_IN_INTERVAL)
                ->withPredicate(fn ($value, $start, $end) => $value > $start && $value < $end)
                ->withParams(['start' => $start, 'end' => $end])
                ->build()
        );
    }
}
