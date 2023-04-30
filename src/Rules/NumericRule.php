<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Interfaces\NumericRuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\CheckName;

class NumericRule extends Rule implements NumericRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(new Check(
            CheckName::NUMERIC,
            CheckErrorName::NOT_NUMERIC,
            fn ($value) => \is_numeric($value)
        ), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function number(): self
    {
        return $this->check(new Check(
            CheckName::NUMBER,
            CheckErrorName::NOT_NUMBER,
            fn ($value) => \is_int($value) || \is_float($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function string(): self
    {
        return $this->check(new Check(
            CheckName::STRING,
            CheckErrorName::NOT_STRING,
            fn ($value) => \is_string($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function positive(): self
    {
        return $this->check(new Check(
            CheckName::POSITIVE,
            CheckErrorName::NOT_POSITIVE,
            fn ($value) => $value > 0
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonPositive(): self
    {
        return $this->check(new Check(
            CheckName::NON_POSITIVE,
            CheckErrorName::NOT_NON_POSITIVE,
            fn ($value) => $value <= 0
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonNegative(): self
    {
        return $this->check(new Check(
            CheckName::NON_NEGATIVE,
            CheckErrorName::NOT_NON_NEGATIVE,
            fn ($value) => $value >= 0
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function negative(): self
    {
        return $this->check(new Check(
            CheckName::NEGATIVE,
            CheckErrorName::NOT_NEGATIVE,
            fn ($value) => $value < 0
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function greaterTran($number): NumericRuleInterface
    {
        return $this->check(new Check(
            CheckName::GREATER,
            CheckErrorName::NOT_GREATER,
            fn ($value) => $value > $number,
            ['number' => $number]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function greaterOrEqual($number): NumericRuleInterface
    {
        return $this->check(new Check(
            CheckName::GREATER_OR_EQUEAL,
            CheckErrorName::NOT_GREATER_OR_EQUEAL,
            fn ($value) => $value >= $number,
            ['number' => $number]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lessTran($number): NumericRuleInterface
    {
        return $this->check(new Check(
            CheckName::LESS,
            CheckErrorName::NOT_LESS,
            fn ($value) => $value < $number,
            ['number' => $number]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lessOrEqual($number): NumericRuleInterface
    {
        return $this->check(new Check(
            CheckName::LESS_OR_EQUEAL,
            CheckErrorName::NOT_LESS_OR_EQUEAL,
            fn ($value) => $value <= $number,
            ['number' => $number]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function between($start, $end): self
    {
        return $this->check(new Check(
            CheckName::BETWEEN,
            CheckErrorName::NOT_BETWEEN,
            fn ($value) => $value >= $start && $value <= $end,
            ['start' => $start, 'end' => $end]
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function inInterval($start, $end): self
    {
        return $this->check(new Check(
            CheckName::IN_INTERVAL,
            CheckErrorName::NOT_IN_INTERVAL,
            fn ($value) => $value > $start && $value < $end,
            ['start' => $start, 'end' => $end]
        ));
    }
}
