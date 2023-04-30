<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Interfaces\StringRuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\CheckName;

class StringRule extends Rule implements StringRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(new Check(
            CheckName::STRING,
            CheckErrorName::NOT_STRING,
            fn ($value) => \is_string($value)
        ), true);
    }

    public function numeric(): StringRuleInterface
    {
        return $this->check(new Check(
            CheckName::NUMERIC,
            CheckErrorName::NOT_NUMERIC,
            fn ($value) => is_numeric($value)
        ));
    }

    public function empty(): StringRuleInterface
    {
        return $this->check(new Check(
            CheckName::EMPTY,
            CheckErrorName::NOT_EMPTY,
            fn ($value) => $value === ''
        ));
    }

    public function notEmpty(): StringRuleInterface
    {
        return $this->check(new Check(
            CheckName::NOT_EMPTY,
            CheckErrorName::EMPTY,
            fn ($value) => $value !== ''
        ));
    }

    public function match(string $regex): StringRuleInterface
    {
        return $this->check(new Check(
            CheckName::MATCH,
            CheckErrorName::NOT_MATCH,
            fn ($value) => \preg_match($regex, $value)
        ));
    }

    public function hasSubstring(string $substr): StringRuleInterface
    {
        return $this->check(new Check(
            CheckName::HAS_SUBSTRING,
            CheckErrorName::HAS_NOT_SUBSTRING,
            fn ($value) => \mb_strpos($value, $substr) !== false
        ));
    }

    public function startsWith(string $substr): StringRuleInterface
    {
        return $this->check(new Check(
            CheckName::STARTS_WITH,
            CheckErrorName::NOT_STARTS_WITH,
            fn ($value) => \mb_strpos($value, $substr) === 0
        ));
    }

    public function endsWith(string $substr): StringRuleInterface
    {
        return $this->check(new Check(
            CheckName::STARTS_WITH,
            CheckErrorName::NOT_STARTS_WITH,
            fn ($value) => \mb_strpos($value, $substr) === \mb_strlen($value) - \mb_strlen($substr)
        ));
    }

    public function lengthIs(IntegerRuleInterface $rule): StringRuleInterface
    {
        return $this->check(new Check(
            CheckName::LENGTH_IS,
            CheckErrorName::BAD_LENGTH,
            static function ($value) use ($rule) {
                /** @var string $value */
                $rule->validate(\mb_strlen($value));
                return true;
            }
        ));
    }
}
