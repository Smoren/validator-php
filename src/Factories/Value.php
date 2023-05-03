<?php

declare(strict_types=1);

namespace Smoren\Validator\Factories;

use Smoren\Validator\Interfaces\BoolRuleInterface;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Interfaces\CompositeRuleInterface;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Interfaces\FloatRuleInterface;
use Smoren\Validator\Interfaces\NumericRuleInterface;
use Smoren\Validator\Interfaces\StringRuleInterface;
use Smoren\Validator\Rules\AndRule;
use Smoren\Validator\Rules\BoolRule;
use Smoren\Validator\Rules\ContainerRule;
use Smoren\Validator\Rules\FloatRule;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\MixedRule;
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
     * @return NumericRuleInterface
     */
    public static function integer(string $name = RuleName::INTEGER): NumericRuleInterface
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
     * @return BoolRuleInterface
     */
    public static function bool(string $name = RuleName::BOOL): BoolRuleInterface
    {
        return new BoolRule($name);
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
     * @param string $name
     *
     * @return MixedRuleInterface
     */
    public static function mixed(string $name = RuleName::CONTAINER): MixedRuleInterface
    {
        return new MixedRule($name);
    }

    /**
     * @param array<MixedRuleInterface> $rules
     * @param string $name
     *
     * @return CompositeRuleInterface
     */
    public static function or(array $rules, string $name = RuleName::OR): CompositeRuleInterface
    {
        return new OrRule($rules, $name);
    }

    /**
     * @param array<MixedRuleInterface> $rules
     * @param string $name
     *
     * @return CompositeRuleInterface
     */
    public static function and(array $rules, string $name = RuleName::AND): CompositeRuleInterface
    {
        return new AndRule($rules, $name);
    }
}
