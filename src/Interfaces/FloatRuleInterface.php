<?php

namespace Smoren\Validator\Interfaces;

interface FloatRuleInterface extends NumberRuleInterface
{
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
