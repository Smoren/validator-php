<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\Checks\BooleanCheckFactory;
use Smoren\Validator\Interfaces\BooleanRuleInterface;

final class BooleanRule extends MixedRule implements BooleanRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(BooleanCheckFactory::getBooleanCheck(), true);
    }
}
