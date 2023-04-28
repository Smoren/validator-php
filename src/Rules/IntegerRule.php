<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\Check;

class IntegerRule extends NumericRule implements IntegerRuleInterface
{
    public const ERROR_NOT_INTEGER = 'not_integer';
    public const ERROR_NOT_EVEN = 'not_even';
    public const ERROR_NOT_ODD = 'not_odd';

    public function __construct()
    {
        $this->addCheck(new Check(
            self::ERROR_NOT_INTEGER,
            fn ($value) => is_int($value),
            [],
            true
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function even(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_EVEN,
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_ODD,
            fn ($value) => $value % 2 !== 0
        ));
    }
}
