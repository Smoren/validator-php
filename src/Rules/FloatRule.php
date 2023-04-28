<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Interfaces\FloatRuleInterface;

class FloatRule extends NumericRule implements FloatRuleInterface
{
    public const ERROR_NOT_FLOAT = 'not_float';
    public const ERROR_FRACTIONAL = 'fractional';
    public const ERROR_NOT_FRACTIONAL = 'not_fractional';
    public const ERROR_NOT_INFINITE = 'not_infinite';
    public const ERROR_NOT_FINITE = 'not_finite';

    public function __construct()
    {
        $this->addCheck(new Check(
            self::ERROR_NOT_FLOAT,
            fn ($value) => is_float($value),
            []
        ), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function fractional(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_FRACTIONAL,
            fn ($value) => \abs($value - \round($value)) >= PHP_FLOAT_EPSILON
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonFractional(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_FRACTIONAL,
            fn ($value) => \abs($value - \round($value)) < PHP_FLOAT_EPSILON
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function finite(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_FINITE,
            fn ($value) => $value > -INF && $value < INF,
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function infinite(): self
    {
        return$this->addCheck(new Check(
            self::ERROR_NOT_INFINITE,
            fn ($value) => $value === -INF || $value === INF,
        ));
    }
}
