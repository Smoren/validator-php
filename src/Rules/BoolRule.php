<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\BoolRuleInterface;
use Smoren\Validator\Structs\CheckName;

class BoolRule extends Rule implements BoolRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(
            CheckBuilder::create(CheckName::BOOL)
                ->withPredicate(fn ($value) => \is_bool($value))
                ->build(),
            true
        );
    }
}
