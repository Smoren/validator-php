<?php

declare(strict_types=1);

namespace Smoren\Validator\Factories;

use Smoren\Validator\Interfaces\BaseRuleInterface;
use Smoren\Validator\Interfaces\CompositeRuleInterface;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Interfaces\FloatRuleInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Rules\AndRule;
use Smoren\Validator\Rules\ContainerRule;
use Smoren\Validator\Rules\FloatRule;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\NumericRule;
use Smoren\Validator\Rules\OrRule;

class Value
{
    /**
     * @return NumericRule
     */
    public static function numeric(): NumericRule
    {
        return new NumericRule();
    }

    /**
     * @return IntegerRuleInterface
     */
    public static function integer(): IntegerRuleInterface
    {
        return new IntegerRule();
    }

    /**
     * @return FloatRuleInterface
     */
    public static function float(): FloatRuleInterface
    {
        return new FloatRule();
    }

    /**
     * @return ContainerRuleInterface
     */
    public static function container(): ContainerRuleInterface
    {
        return new ContainerRule();
    }

    /**
     * @param array<BaseRuleInterface> $rules
     *
     * @return CompositeRuleInterface
     */
    public static function or(array $rules): CompositeRuleInterface
    {
        return new OrRule($rules);
    }

    /**
     * @param array<BaseRuleInterface> $rules
     *
     * @return CompositeRuleInterface
     */
    public static function and(array $rules): CompositeRuleInterface
    {
        return new AndRule($rules);
    }
}
