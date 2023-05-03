<?php

declare(strict_types=1);

namespace Smoren\Validator\Factories;

use Smoren\Validator\Interfaces\BooleanRuleInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Interfaces\CompositeRuleInterface;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Interfaces\FloatRuleInterface;
use Smoren\Validator\Interfaces\NumericRuleInterface;
use Smoren\Validator\Interfaces\StringRuleInterface;
use Smoren\Validator\Rules\AllOfRule;
use Smoren\Validator\Rules\BooleanRule;
use Smoren\Validator\Rules\ContainerRule;
use Smoren\Validator\Rules\FloatRule;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\MixedRule;
use Smoren\Validator\Rules\NumericRule;
use Smoren\Validator\Rules\StringRule;
use Smoren\Validator\Rules\AnyOfRule;
use Smoren\Validator\Structs\RuleName;

class Value
{
    /**
     * @param string $name
     *
     * @return NumericRuleInterface
     */
    public static function numeric(string $name = RuleName::NUMERIC): NumericRuleInterface
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
     * @return BooleanRuleInterface
     */
    public static function boolean(string $name = RuleName::BOOLEAN): BooleanRuleInterface
    {
        return new BooleanRule($name);
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
    public static function anyOf(array $rules, string $name = RuleName::OR): CompositeRuleInterface
    {
        return new AnyOfRule($rules, $name);
    }

    /**
     * @param array<MixedRuleInterface> $rules
     * @param string $name
     *
     * @return CompositeRuleInterface
     */
    public static function allOf(array $rules, string $name = RuleName::AND): CompositeRuleInterface
    {
        return new AllOfRule($rules, $name);
    }
}
