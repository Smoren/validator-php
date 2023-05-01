<?php

declare(strict_types=1);

namespace Smoren\Validator\Factories;

use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Interfaces\CompositeRuleInterface;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Interfaces\FloatRuleInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Interfaces\StringRuleInterface;
use Smoren\Validator\Rules\AndRule;
use Smoren\Validator\Rules\ContainerRule;
use Smoren\Validator\Rules\FloatRule;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\NumericRule;
use Smoren\Validator\Rules\StringRule;
use Smoren\Validator\Rules\OrRule;
use Smoren\Validator\Structs\RuleName;

class Value
{
    /**
     * @param string $name
     *
     * @return NumericRule
     */
    public static function numeric(string $name = RuleName::NUMERIC): NumericRule
    {
        return new NumericRule($name);
    }

    /**
     * @param string $name
     *
     * @return IntegerRuleInterface
     */
    public static function integer(string $name = RuleName::INTEGER): IntegerRuleInterface
    {
        return new IntegerRule($name);
    }

    /**
     * @param string $name
     *
     * @return FloatRuleInterface
     */
    public static function float(string $name = RuleName::FLOAT): FloatRuleInterface
    {
        return new FloatRule($name);
    }

    /**
     * @param string $name
     *
     * @return StringRuleInterface
     */
    public static function string(string $name = RuleName::STRING): StringRuleInterface
    {
        return new StringRule($name);
    }

    /**
     * @param string $name
     *
     * @return ContainerRuleInterface
     */
    public static function container(string $name = RuleName::CONTAINER): ContainerRuleInterface
    {
        return new ContainerRule($name);
    }

    /**
     * @param array<RuleInterface> $rules
     * @param string $name
     *
     * @return CompositeRuleInterface
     */
    public static function or(array $rules, string $name = RuleName::OR): CompositeRuleInterface
    {
        return new OrRule($rules, $name);
    }

    /**
     * @param array<RuleInterface> $rules
     * @param string $name
     *
     * @return CompositeRuleInterface
     */
    public static function and(array $rules, string $name = RuleName::AND): CompositeRuleInterface
    {
        return new AndRule($rules, $name);
    }
}
