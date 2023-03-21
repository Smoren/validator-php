<?php

namespace Smoren\Validator\Interfaces;

interface NumberRuleInterface extends UniformRuleInterface
{
    /**
     * @return static
     */
    public function positive(): self;

    /**
     * @return static
     */
    public function nonNegative(): self;

    /**
     * @return static
     */
    public function negative(): self;

    /**
     * @param numeric $start
     * @param numeric $end
     *
     * @return static
     */
    public function inSegment($start, $end): self;

    /**
     * @param numeric $start
     * @param numeric $end
     *
     * @return static
     */
    public function inInterval($start, $end): self;
}
