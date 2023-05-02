<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\CheckName;

class IntegerRule extends NumericRule implements IntegerRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        Rule::__construct($name);
        $this->check(
            CheckBuilder::create(CheckName::INTEGER, CheckErrorName::NOT_INTEGER)
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
            CheckBuilder::create(CheckName::EVEN, CheckErrorName::NOT_EVEN)
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
            CheckBuilder::create(CheckName::ODD, CheckErrorName::NOT_ODD)
                ->withPredicate(fn ($value) => $value % 2 !== 0)
                ->build()
        );
    }
}
