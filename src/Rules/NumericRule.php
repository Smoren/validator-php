<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\NumericRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class NumericRule extends MixedRule implements NumericRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->check(
            $this->getNumericCheck(),
            true
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function number(bool $stopOnViolation = true): self
    {
        return $this->check(
            $this->getNumberCheck(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function string(bool $stopOnViolation = true): self
    {
        return $this->check(
            $this->getStringCheck(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function integer(bool $stopOnViolation = true): self
    {
        return $this->check(
            $this->getIntegerCheck(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function float(bool $stopOnViolation = true): self
    {
        return $this->check(
            $this->getFloatCheck(),
            $stopOnViolation
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
    public function greaterThan($number): NumericRuleInterface
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
    public function lessThan($number): NumericRuleInterface
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

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function fractional(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::FRACTIONAL)
                ->withPredicate(fn ($value) => \abs($value - \round(\floatval($value))) >= \PHP_FLOAT_EPSILON)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonFractional(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NON_FRACTIONAL)
                ->withPredicate(fn ($value) => \abs($value - \round(\floatval($value))) < \PHP_FLOAT_EPSILON)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function finite(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::FINITE)
                ->withPredicate(fn ($value) => $value > -INF && $value < INF)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function infinite(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::INFINITE)
                ->withPredicate(fn ($value) => $value === -INF || $value === INF)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function even(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::EVEN)
                ->withPredicate(fn ($value) => $value % 2 === 0)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function odd(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ODD)
                ->withPredicate(fn ($value) => $value % 2 !== 0)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nan(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NAN)
                ->withPredicate(fn ($value) => \is_nan(\floatval($value)))
                ->withDependOnChecks([$this->getNumericCheck()])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function notNan(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NOT_NAN)
                ->withPredicate(fn ($value) => !\is_nan(\floatval($value)))
                ->withDependOnChecks([$this->getNumericCheck()])
                ->build()
        );
    }

    protected function getNumericCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NUMERIC)
            ->withPredicate(fn ($value) => \is_numeric($value))
            ->build();
    }

    protected function getNumberCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NUMBER)
            ->withPredicate(fn ($value) => \is_int($value) || \is_float($value))
            ->build();
    }

    protected function getStringCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::STRING)
            ->withPredicate(fn ($value) => \is_string($value))
            ->build();
    }

    protected function getIntegerCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::INTEGER)
            ->withPredicate(fn ($value) => \is_int($value))
            ->build();
    }

    protected function getFloatCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::FLOAT)
            ->withPredicate(fn ($value) => \is_float($value))
            ->build();
    }
}
