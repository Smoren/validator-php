<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\NumberRuleInterface;
use Smoren\Validator\Structs\Check;

abstract class NumberRule extends Rule implements NumberRuleInterface
{
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
     * {@inheritDoc}
     *
     * @return static
     */
    public function positive(): self
    {
        return $this->add(new Check(
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
        return $this->add(new Check(
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
        return $this->add(new Check(
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
        return $this->add(new Check(
            self::ERROR_NOT_NEGATIVE,
            fn ($value) => $value < 0
        ));
    }


    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function greaterTran($number): NumberRuleInterface
    {
        return $this->add(new Check(
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
    public function greaterOrEqual($number): NumberRuleInterface
    {
        return $this->add(new Check(
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
    public function lessTran($number): NumberRuleInterface
    {
        return $this->add(new Check(
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
    public function lessOrEqual($number): NumberRuleInterface
    {
        return $this->add(new Check(
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
        return $this->add(new Check(
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
        return $this->add(new Check(
            self::ERROR_NOT_IN_INTERVAL,
            fn ($value) => $value > $start && $value < $end,
            ['start' => $start, 'end' => $end]
        ));
    }
}
