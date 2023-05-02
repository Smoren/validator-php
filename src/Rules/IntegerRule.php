<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\CheckName;

class IntegerRule extends NumericMixedRule implements IntegerRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        MixedRule::__construct($name);
        $this->check(
            CheckBuilder::create(CheckName::INTEGER)
                ->withPredicate(fn ($value) => \is_int($value))
                ->build(),
            true
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function even(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::EVEN)
                ->withPredicate(fn ($value) => $value % 2 === 0)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function odd(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ODD)
                ->withPredicate(fn ($value) => $value % 2 !== 0)
                ->build()
        );
    }
}
