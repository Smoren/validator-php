<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\BaseRuleInterface;
use Smoren\Validator\Interfaces\ExecutionResultInterface;
use Smoren\Validator\Structs\ExecutionResult;

abstract class BaseRule implements BaseRuleInterface
{
    /**
     * {@inheritDoc}
     */
    public function validate($value): void
    {
        $this->execute($value);
    }

    /**
     * @param mixed $value
     *
     * @return ExecutionResultInterface
     */
    protected function execute($value): ExecutionResultInterface
    {
        return new ExecutionResult(false);
    }
}
