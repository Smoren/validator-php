<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\NumericRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class NumericMixedRule extends MixedRule implements NumericRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->check(
            CheckBuilder::create(CheckName::NUMERIC)
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
            CheckBuilder::create(CheckName::NUMBER)
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
            CheckBuilder::create(CheckName::STRING)
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
            CheckBuilder::create(CheckName::TRUTHY)
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
            CheckBuilder::create(CheckName::FALSY)
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
            CheckBuilder::create(CheckName::POSITIVE)
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
            CheckBuilder::create(CheckName::NON_POSITIVE)
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
            CheckBuilder::create(CheckName::NON_NEGATIVE)
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
            CheckBuilder::create(CheckName::NEGATIVE)
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
            CheckBuilder::create(CheckName::GREATER)
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
            CheckBuilder::create(CheckName::GREATER_OR_EQUEAL)
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
            CheckBuilder::create(CheckName::LESS)
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
            CheckBuilder::create(CheckName::LESS_OR_EQUEAL)
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
            CheckBuilder::create(CheckName::BETWEEN)
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
            CheckBuilder::create(CheckName::IN_INTERVAL)
                ->withPredicate(fn ($value, $start, $end) => $value > $start && $value < $end)
                ->withParams(['start' => $start, 'end' => $end])
                ->build()
        );
    }
}
