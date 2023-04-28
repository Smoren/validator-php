<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\NumericRuleInterface;
use Smoren\Validator\Structs\Check;

class NumericRule extends Rule implements NumericRuleInterface
{
    public const ERROR_NOT_NUMERIC = 'not_numeric';
    public const ERROR_NOT_NUMBER = 'not_number';
    public const ERROR_NOT_STRING = 'not_string';
    public const ERROR_NOT_POSITIVE = 'not_positive';
    public const ERROR_NOT_NON_POSITIVE = 'not_non_positive';
    public const ERROR_NOT_NON_NEGATIVE = 'not_non_negative';
    public const ERROR_NOT_NEGATIVE = 'not_negative';
    public const ERROR_NOT_GREATER = 'not_greater';
    public const ERROR_NOT_GREATER_OR_EQUEAL = 'not_greater_or_equal';
    public const ERROR_NOT_LESS = 'not_less';
    public const ERROR_NOT_LESS_OR_EQUEAL = 'not_less_or_equal';
    public const ERROR_NOT_IN_SEGMENT = 'not_in_segment';
    public const ERROR_NOT_IN_INTERVAL = 'not_in_interval';

    /**
     * NumericRule constructor.
     */
    public function __construct()
    {
        $this->addCheck(new Check(
            self::ERROR_NOT_NUMERIC,
            fn ($value) => is_numeric($value),
            [],
            true
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function number(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_NUMBER,
            fn ($value) => is_int($value) || is_float($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function string(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_STRING,
            fn ($value) => is_string($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function positive(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_POSITIVE,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_NON_POSITIVE,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_NON_NEGATIVE,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_NEGATIVE,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_GREATER,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_GREATER_OR_EQUEAL,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_LESS,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_LESS_OR_EQUEAL,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_IN_SEGMENT,
            fn ($value) => $value >= $start && $value <= $end,
            ['start' => $start, 'end' => $end]
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function inInterval($start, $end): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_IN_INTERVAL,
            fn ($value) => $value > $start && $value < $end,
            ['start' => $start, 'end' => $end]
        ));
    }
}
