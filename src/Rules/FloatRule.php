<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\FloatRuleInterface;
use Smoren\Validator\Structs\CheckName;

class FloatRule extends NumericMixedRule implements FloatRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        MixedRule::__construct($name);
        $this->check(
            CheckBuilder::create(CheckName::FLOAT)
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
            CheckBuilder::create(CheckName::FRACTIONAL)
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
            CheckBuilder::create(CheckName::NON_FRACTIONAL)
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
            CheckBuilder::create(CheckName::FINITE)
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
            CheckBuilder::create(CheckName::INFINITE)
                ->withPredicate(fn ($value) => $value === -INF || $value === INF)
                ->build()
        );
    }
}
