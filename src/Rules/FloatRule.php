<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Interfaces\FloatRuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\CheckName;

class FloatRule extends NumericRule implements FloatRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        Rule::__construct($name);
        $this->check(new Check(
            CheckName::FLOAT,
            CheckErrorName::NOT_FLOAT,
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
        return $this->check(new Check(
            CheckName::FRACTIONAL,
            CheckErrorName::NOT_FRACTIONAL,
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
        return $this->check(new Check(
            CheckName::NOT_FRACTIONAL,
            CheckErrorName::FRACTIONAL,
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
        return $this->check(new Check(
            CheckName::FINITE,
            CheckErrorName::NOT_FINITE,
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
        return$this->check(new Check(
            CheckName::INFINITE,
            CheckErrorName::NOT_INFINITE,
            fn ($value) => $value === -INF || $value === INF,
        ));
    }
}
