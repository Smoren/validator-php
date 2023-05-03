<?php

namespace Smoren\Validator\Factories\Checks;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\CheckName;

class StringCheckFactory
{
    public static function getStringCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::STRING)
            ->withPredicate(fn ($value) => \is_string($value))
            ->build();
    }

    public static function getNumericCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NUMERIC)
            ->withPredicate(fn($value) => \is_numeric($value))
            ->build();
    }

    public static function getEmptyCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::EMPTY)
            ->withPredicate(fn($value) => $value === '')
            ->build();
    }

    public static function getNotEmptyCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NOT_EMPTY)
            ->withPredicate(fn($value) => $value !== '')
            ->build();
    }

    public static function getMatchCheck(string $regex): CheckInterface
    {
        return CheckBuilder::create(CheckName::MATCH)
            ->withPredicate(fn($value, string $regex) => \boolval(\preg_match($regex, $value)))
            ->withParams(['regex' => $regex])
            ->build();
    }

    public static function getHasSubstringCheck(string $substr): CheckInterface
    {
        return CheckBuilder::create(CheckName::HAS_SUBSTRING)
            ->withPredicate(fn($value, string $substr) => \mb_strpos($value, $substr) !== false)
            ->withParams(['substring' => $substr])
            ->build();
    }

    public static function getStartsWithCheck(string $substr): CheckInterface
    {
        return CheckBuilder::create(CheckName::STARTS_WITH)
            ->withPredicate(fn($value, string $substr) => \mb_strpos($value, $substr) === 0)
            ->withParams(['substring' => $substr])
            ->build();
    }

    public static function getEndsWithCheck(string $substr): CheckInterface
    {
        return CheckBuilder::create(CheckName::ENDS_WITH)
            ->withPredicate(static function ($value, string $substr) {
                return \substr($value, \mb_strlen($value) - \mb_strlen($substr)) === $substr;
            })
            ->withParams(['substring' => $substr])
            ->build();
    }

    public static function getLengthIsCheck(IntegerRuleInterface $rule): CheckInterface
    {
        return CheckBuilder::create(CheckName::LENGTH_IS)
            ->withPredicate(static function ($value) use ($rule) {
                $rule->validate(\mb_strlen($value));
                return true;
            })
            ->build();
    }
}
