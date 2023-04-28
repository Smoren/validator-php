<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface FloatRuleInterface extends NumericRuleInterface
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
