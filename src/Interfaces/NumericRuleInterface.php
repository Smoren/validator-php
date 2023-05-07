<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface NumericRuleInterface extends MixedRuleInterface
{
    /**
     * @return static
     */
    public function number(): self;

    /**
     * @return static
     */
    public function string(): self;

    /**
     * @return static
     */
    public function integer(): self;

    /**
     * @return static
     */
    public function float(): self;

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
    public function greaterThan($number): self;

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
    public function lessThan($number): self;

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
    public function inOpenInterval($start, $end): self;

    /**
     * @param numeric $start
     * @param numeric $end
     *
     * @return static
     */
    public function inLeftHalfOpenInterval($start, $end): self;

    /**
     * @param numeric $start
     * @param numeric $end
     *
     * @return static
     */
    public function inRightHalfOpenInterval($start, $end): self;

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

    /**
     * @return static
     */
    public function nan(): self;

    /**
     * @return static
     */
    public function notNan(): self;
}
