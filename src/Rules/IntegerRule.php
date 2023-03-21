<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\Check;

class IntegerRule extends Rule implements IntegerRuleInterface
{
    public function __construct()
    {
        $this->add(new Check(
            'not_integer',
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
    public function even(): self
    {
        return $this->add(new Check(
            'not_even',
            fn ($value) => $value % 2 === 0
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
            'not_odd',
            fn ($value) => $value % 2 !== 0
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
            'not_positive',
            fn ($value) => $value > 0
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
            'not_non_negative',
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
            'not_negative',
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
            'not_in_segment',
            fn ($value) => $value >= $start && $value <= $end
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function inInterval($start, $end): self
    {
        return $this->add(new Check(
            'not_in_interval',
            fn ($value) => $value >= $start && $value <= $end
        ));
    }
}
