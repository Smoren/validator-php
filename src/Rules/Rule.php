<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Exceptions\StopValidationException;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\Check;

class Rule extends NullableRule implements RuleInterface
{
    /**
     * @var array<CheckInterface>
     */
    protected array $checks = [];

    /**
     * {@inheritDoc}
     */
    public function validate($value): void
    {
        try {
            parent::validate($value);
        } catch (StopValidationException $e) {
            return;
        }

        $errors = [];

        foreach ($this->checks as $check) {
            try {
                $check->execute($value);
            } catch (CheckError $e) {
                $errors[] = $e;
                if ($check->isInterrupting()) {
                    throw ValidationError::fromCheckErrors($value, $errors);
                }
            }
        }

        if (\count($errors) > 0) {
            throw ValidationError::fromCheckErrors($value, $errors);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function add(CheckInterface $check): self
    {
        $this->checks[] = $check;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function check(string $name, callable $predicate, array $params = [], bool $isBlocking = false): self
    {
        return $this->add(new Check($name, $predicate, $params, $isBlocking));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stopOnFail(): self
    {
        if (\count($this->checks) > 0) {
            $this->checks[\count($this->checks) - 1]->setInterrupting();
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stopIfAnyPreviousFails(): self
    {
        foreach ($this->checks as $check) {
            $check->setInterrupting();
        }
        return $this;
    }
}
