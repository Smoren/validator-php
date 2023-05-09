<?php

declare(strict_types=1);

namespace Smoren\Validator\Factories\Checks;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

final class MixedCheckFactory
{
    public static function getTruthyCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::TRUTHY)
            ->withPredicate(fn ($value) => \boolval($value))
            ->build();
    }

    public static function getFalsyCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::FALSY)
            ->withPredicate(fn ($value) => !\boolval($value))
            ->build();
    }

    /**
     * @param mixed $value
     * @return CheckInterface
     */
    public static function getEqualCheck($value): CheckInterface
    {
        return CheckBuilder::create(CheckName::EQUAL)
            ->withPredicate(fn ($actual, $expected) => $actual == $expected)
            ->withParams([Param::EXPECTED => $value])
            ->build();
    }

    /**
     * @param mixed $value
     * @return CheckInterface
     */
    public static function getSameCheck($value): CheckInterface
    {
        return CheckBuilder::create(CheckName::SAME)
            ->withPredicate(fn ($actual, $expected) => $actual === $expected)
            ->withParams([Param::EXPECTED => $value])
            ->build();
    }
}
