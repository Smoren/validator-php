<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Interfaces\StringRuleInterface;
use Smoren\Validator\Structs\CheckName;

class StringRule extends Rule implements StringRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(
            CheckBuilder::create(CheckName::STRING)
                ->withPredicate(fn ($value) => \is_string($value))
                ->build(),
            true
        );
    }

    public function numeric(): StringRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::NUMERIC)
                ->withPredicate(fn ($value) => \is_numeric($value))
                ->build()
        );
    }

    public function empty(): StringRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::EMPTY)
                ->withPredicate(fn ($value) => $value === '')
                ->build()
        );
    }

    public function notEmpty(): StringRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::NOT_EMPTY)
                ->withPredicate(fn ($value) => $value !== '')
                ->build()
        );
    }

    public function match(string $regex): StringRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::MATCH)
                ->withPredicate(fn ($value, string $regex) => \boolval(\preg_match($regex, $value)))
                ->withParams(['regex' => $regex])
                ->build()
        );
    }

    public function hasSubstring(string $substr): StringRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::HAS_SUBSTRING)
                ->withPredicate(fn ($value, string $substr) => \mb_strpos($value, $substr) !== false)
                ->withParams(['substring' => $substr])
                ->build()
        );
    }

    public function startsWith(string $substr): StringRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::STARTS_WITH)
                ->withPredicate(fn ($value, string $substr) => \mb_strpos($value, $substr) === 0)
                ->withParams(['substring' => $substr])
                ->build()
        );
    }

    public function endsWith(string $substr): StringRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::ENDS_WITH)
                ->withPredicate(static function ($value, string $substr) {
                    return \substr($value, \mb_strlen($value) - \mb_strlen($substr)) === $substr;
                })
                ->withParams(['substring' => $substr])
                ->build()
        );
    }

    public function lengthIs(IntegerRuleInterface $rule): StringRuleInterface
    {
        return $this->check(
            CheckBuilder::create(CheckName::LENGTH_IS)
                ->withPredicate(static function ($value) use ($rule) {
                    /** @var string $value */
                    $rule->validate(\mb_strlen($value));
                    return true;
                })
                ->build()
        );
    }
}
