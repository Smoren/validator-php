<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface NumericRuleInterface extends MixedRuleInterface
{
    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function number(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function string(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function integer(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function float(bool $stopOnViolation = true): self;

    /**
     * @return static
     */
    public function positive(): self;

    /**
     * @return static
     */
    public function nonPositive(): self;

    /**
     * @return static
     */
    public function nonNegative(): self;

    /**
     * @return static
     */
    public function negative(): self;

    /**
     * @param numeric $number
     *
     * @return static
     */
    public function greaterTran($number): self;

    /**
     * @param numeric $number
     *
     * @return static
     */
    public function greaterOrEqual($number): self;

    /**
     * @param numeric $number
     *
     * @return static
     */
    public function lessTran($number): self;

    /**
     * @param numeric $number
     *
     * @return static
     */
    public function lessOrEqual($number): self;

    /**
     * @param numeric $start
     * @param numeric $end
     *
     * @return static
     */
    public function between($start, $end): self;

    /**
     * @param numeric $start
     * @param numeric $end
     *
     * @return static
     */
    public function inInterval($start, $end): self;

    /**
     * @return static
     */
    public function even(): self;

    /**
     * @return static
     */
    public function odd(): self;

    /**
     * @return static
     */
    public function fractional(): self;

    /**
     * @return static
     */
    public function nonFractional(): self;

    /**
     * @return static
     */
    public function finite(): self;

    /**
     * @return static
     */
    public function infinite(): self;
}
