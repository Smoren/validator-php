<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\MixedRuleInterface;

abstract class CompositeCheck implements CheckInterface
{
    /**
     * @var array<MixedRuleInterface>
     */
    protected array $rules;

    /**
     * @param array<MixedRuleInterface> $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ValidationError if nested rules fail
     */
    abstract public function execute($value, array $previousErrors, bool $preventDuplicate = false): void;
}
