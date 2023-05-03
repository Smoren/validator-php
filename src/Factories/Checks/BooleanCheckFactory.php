<?php

namespace Smoren\Validator\Factories\Checks;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Structs\CheckName;

class BooleanCheckFactory
{
    public static function getBooleanCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::BOOLEAN)
            ->withPredicate(fn ($value) => \is_bool($value))
            ->build();
    }
}