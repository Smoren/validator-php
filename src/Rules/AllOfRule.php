<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\AllOfCheck;
use Smoren\Validator\Interfaces\CompositeRuleInterface;
use Smoren\Validator\Interfaces\MixedRuleInterface;

final class AllOfRule extends MixedRule implements CompositeRuleInterface
{
    /**
     * @param string $name
     * @param array<MixedRuleInterface> $rules
     */
    public function __construct(string $name, array $rules)
    {
        parent::__construct($name);
        $this->check(new AllOfCheck($rules));
    }
}
