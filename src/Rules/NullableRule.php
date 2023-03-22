<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Exceptions\StopValidationException;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\BaseRuleInterface;

abstract class NullableRule implements BaseRuleInterface
{
    public const ERROR_NULL = 'null';

    /**
     * @var bool
     */
    protected bool $isNullable = false;

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nullable(): self
    {
        $this->isNullable = true;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws StopValidationException
     */
    public function validate($value): void
    {
        if ($value === null) {
            if ($this->isNullable) {
                throw new StopValidationException();
            }

            throw new ValidationError($value, [[self::ERROR_NULL, []]]);
        }
    }
}
