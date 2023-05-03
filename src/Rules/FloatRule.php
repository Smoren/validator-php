<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\FloatRuleInterface;

class FloatRule extends NumericRule implements FloatRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        MixedRule::__construct($name);
        $this->float();
    }
}
