<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\UniformRuleInterface;
use Smoren\Validator\Structs\Check;

class Rule implements UniformRuleInterface
{
    /**
     * @var array<CheckInterface>
     */
    protected array $checks = [];
    /**
     * @var array<CheckInterface>
     */
    protected array $blockingChecks = [];

    /**
     * {@inheritDoc}
     */
    public function validate($value): void
    {
        foreach ($this->blockingChecks as $check) {
            try {
                $check->execute($value);
            } catch (CheckError $e) {
                throw ValidationError::fromCheckErrors($value, [$e]);
            }
        }

        $errors = [];

        foreach ($this->checks as $check) {
            try {
                $check->execute($value);
            } catch (CheckError $e) {
                $errors[] = $e;
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
        if ($check->isBlocking()) {
            $this->blockingChecks[] = $check;
        } else {
            $this->checks[] = $check;
        }
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
}
