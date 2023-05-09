<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\Checks\StringCheckFactory;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Interfaces\StringRuleInterface;

class StringRule extends MixedRule implements StringRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(StringCheckFactory::getStringCheck(), true);
    }

    public function numeric(): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getNumericCheck(), true);
    }

    public function empty(): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getEmptyCheck());
    }

    public function notEmpty(): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getNotEmptyCheck());
    }

    public function match(string $regex): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getMatchCheck($regex));
    }

    public function uuid(): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getUuidCheck());
    }

    public function hasSubstring(string $substr): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getHasSubstringCheck($substr));
    }

    public function startsWith(string $substr): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getStartsWithCheck($substr));
    }

    public function endsWith(string $substr): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getEndsWithCheck($substr));
    }

    public function lengthIs(IntegerRuleInterface $rule): StringRuleInterface
    {
        return $this->check(StringCheckFactory::getLengthIsCheck($rule));
    }
}
