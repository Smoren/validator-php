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
}
