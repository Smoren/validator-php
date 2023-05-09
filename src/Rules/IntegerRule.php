<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\IntegerRuleInterface;

final class IntegerRule extends NumericRule implements IntegerRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        MixedRule::__construct($name);
        $this->integer();
    }
}
