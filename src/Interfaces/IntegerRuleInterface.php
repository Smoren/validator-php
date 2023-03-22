<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface IntegerRuleInterface extends NumberRuleInterface
{
    /**
     * @return static
     */
    public function even(): self;

    /**
     * @return static
     */
    public function odd(): self;
}
