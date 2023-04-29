<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
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
        $this->check(new Check(
            CheckName::INTEGER,
            CheckErrorName::NOT_INTEGER,
            fn ($value) => is_int($value),
            []
        ), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function even(): self
    {
        return $this->check(new Check(
            CheckName::EVEN,
            CheckErrorName::NOT_EVEN,
            fn ($value) => $value % 2 === 0,
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function odd(): self
    {
        return $this->check(new Check(
            CheckName::ODD,
            CheckErrorName::NOT_ODD,
            fn ($value) => $value % 2 !== 0
        ));
    }
}
