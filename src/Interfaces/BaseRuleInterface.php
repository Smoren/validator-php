<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\ValidationError;

interface BaseRuleInterface
{
    /**
     * @param mixed $value
     *
     * @return void
     *
     * @throws ValidationError
     */
    public function validate($value): void;

    /**
     * @return static
     */
    public function nullable(): self;

    /**
     * @return static
     */
    public function truthy(): self;

    /**
     * @return static
     */
    public function falsy(): self;
}
