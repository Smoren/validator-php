<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface NumericRuleInterface extends MixedRuleInterface
{
    /**
     * Checks if the value is a number.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::NUMBER
     *
     * @return static
     */
    public function number(): self;

    /**
     * Checks if the value is a numeric string.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::STRING
     *
     * @return static
     */
    public function string(): self;

    /**
     * Checks if the value is integer.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::INTEGER
     *
     * @return static
     */
    public function integer(): self;

    /**
     * Checks if the value is float.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::FLOAT
     *
     * @return static
     */
    public function float(): self;

    /**
     * Checks if the value is a positive number.
     *
     * Names of rules that can be violated:
     * - @see CheckName::POSITIVE
     *
     * @return static
     */
    public function positive(): self;

    /**
     * Checks if the value is a non-positive number.
     *
     * Names of rules that can be violated:
     * - @see CheckName::NON_POSITIVE
     *
     * @return static
     */
    public function nonPositive(): self;

    /**
     * Checks if the value is a non-negative number.
     *
     * Names of rules that can be violated:
     * - @see CheckName::NON_NEGATIVE
     *
     * @return static
     */
    public function nonNegative(): self;

    /**
     * Checks if the value is a negative number.
     *
     * Names of rules that can be violated:
     * - @see CheckName::NEGATIVE
     *
     * @return static
     */
    public function negative(): self;

    /**
     * Checks if the value is greater than given number.
     *
     * @param numeric $number
     *
     * Names of rules that can be violated:
     * - @see CheckName::GREATER
     *
     * @return static
     */
    public function greaterThan($number): self;

    /**
     * Checks if the value is greater or equal to given number.
     *
     * @param numeric $number
     *
     * Names of rules that can be violated:
     * - @see CheckName::GREATER_OR_EQUEAL
     *
     * @return static
     */
    public function greaterOrEqual($number): self;

    /**
     * Checks if the value is less than given number.
     *
     * @param numeric $number
     *
     * Names of rules that can be violated:
     * - @see CheckName::LESS
     *
     * @return static
     */
    public function lessThan($number): self;

    /**
     * Checks if the value is less or equal to given number.
     *
     * @param numeric $number
     *
     * Names of rules that can be violated:
     * - @see CheckName::LESS_OR_EQUEAL
     *
     * @return static
     */
    public function lessOrEqual($number): self;

    /**
     * Checks if the value is in closed interval.
     *
     * $start <= $value <= $end`
     *
     * @param numeric $start
     * @param numeric $end
     *
     * Names of rules that can be violated:
     * - @see CheckName::BETWEEN
     *
     * @return static
     */
    public function between($start, $end): self;

    /**
     * Checks if the value is in open interval.
     *
     * `$start < $value < $end`
     *
     * @param numeric $start
     * @param numeric $end
     *
     * Names of rules that can be violated:
     * - @see CheckName::IN_OPEN_INTERVAL
     *
     * @return static
     */
    public function inOpenInterval($start, $end): self;

    /**
     * Checks if the value is in left-half open interval.
     *
     * `$start < $value <= $end`
     *
     * @param numeric $start
     * @param numeric $end
     *
     * Names of rules that can be violated:
     * - @see CheckName::IN_RIGHT_HALF_OPEN_INTERVAL
     *
     * @return static
     */
    public function inLeftHalfOpenInterval($start, $end): self;

    /**
     * Checks if the value is in right-half open interval.
     *
     * `$start <= $value < $end`
     *
     * @param numeric $start
     * @param numeric $end
     *
     * Names of rules that can be violated:
     * - @see CheckName::IN_LEFT_HALF_OPEN_INTERVAL
     *
     * @return static
     */
    public function inRightHalfOpenInterval($start, $end): self;

    /**
     * Checks if the value is in an even number.
     *
     * Names of rules that can be violated:
     * - @see CheckName::EVEN
     *
     * @return static
     */
    public function even(): self;

    /**
     * Checks if the value is in an odd number.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ODD
     *
     * @return static
     */
    public function odd(): self;

    /**
     * Checks if the value is a number that has a fractional part.
     *
     * Names of rules that can be violated:
     * - @see CheckName::FRACTIONAL
     *
     * @return static
     */
    public function fractional(): self;

    /**
     * Checks if the value is a number that has no fractional part.
     *
     * Names of rules that can be violated:
     * - @see CheckName::NON_FRACTIONAL
     *
     * @return static
     */
    public function nonFractional(): self;

    /**
     * Checks if the value is a finite number.
     *
     * Names of rules that can be violated:
     * - @see CheckName::FINITE
     *
     * @return static
     */
    public function finite(): self;

    /**
     * Checks if the value is a infinite number.
     *
     * Names of rules that can be violated:
     * - @see CheckName::INFINITE
     *
     * @return static
     */
    public function infinite(): self;

    /**
     * Checks if the value is NAN.
     *
     * Names of rules that can be violated:
     * - @see CheckName::NAN
     *
     * @return static
     */
    public function nan(): self;

    /**
     * Checks if the value is not NAN.
     *
     * Names of rules that can be violated:
     * - @see CheckName::NOT_NAN
     *
     * @return static
     */
    public function notNan(): self;
}
