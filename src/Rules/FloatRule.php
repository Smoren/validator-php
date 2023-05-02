<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Factories\CheckBuilder;
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
        $this->check(
            CheckBuilder::create(CheckName::FLOAT, CheckErrorName::NOT_FLOAT)
                ->withPredicate(fn ($value) => \is_float($value))
                ->build(),
            true
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function fractional(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::FRACTIONAL, CheckErrorName::NOT_FRACTIONAL)
                ->withPredicate(fn ($value) => \abs($value - \round($value)) >= \PHP_FLOAT_EPSILON)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonFractional(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NOT_FRACTIONAL, CheckErrorName::FRACTIONAL)
                ->withPredicate(fn ($value) => \abs($value - \round($value)) < \PHP_FLOAT_EPSILON)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function finite(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::FINITE, CheckErrorName::NOT_FINITE)
                ->withPredicate(fn ($value) => $value > -INF && $value < INF)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function infinite(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::INFINITE, CheckErrorName::NOT_INFINITE)
                ->withPredicate(fn ($value) => $value === -INF || $value === INF)
                ->build()
        );
    }
}
