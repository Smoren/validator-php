<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\Check;

class IntegerRule extends Rule implements IntegerRuleInterface
{
    public const ERROR_NOT_INTEGER = 'not_integer';
    public const ERROR_NOT_POSITIVE = 'not_positive';
    public const ERROR_NOT_NON_POSITIVE = 'not_non_positive';
    public const ERROR_NOT_NON_NEGATIVE = 'not_non_negative';
    public const ERROR_NOT_NEGATIVE = 'not_negative';
    public const ERROR_NOT_IN_SEGMENT = 'not_in_segment';
    public const ERROR_NOT_IN_INTERVAL = 'not_in_interval';
    public const ERROR_NOT_EVEN = 'not_even';
    public const ERROR_NOT_ODD = 'not_odd';

    public function __construct()
    {
        $this->add(new Check(
            self::ERROR_NOT_INTEGER,
            fn ($value) => is_int($value),
            [],
            true
        ));
    }

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
    public function inSegment($start, $end): self
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

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function even(): self
    {
        return $this->add(new Check(
            self::ERROR_NOT_EVEN,
            fn ($value) => $value % 2 === 0,
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function odd(): self
    {
        return $this->add(new Check(
            self::ERROR_NOT_ODD,
            fn ($value) => $value % 2 !== 0
        ));
    }
}
