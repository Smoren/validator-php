<?php

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\BaseRuleInterface;
use Smoren\Validator\Interfaces\CompositeRuleInterface;

abstract class CompositeRule implements CompositeRuleInterface
{
    /**
     * @var array<BaseRuleInterface>
     */
    protected array $rules = [];

    /**
     * @param array<BaseRuleInterface> $rules
     */
    public function __construct(array $rules)
    {
        foreach ($rules as $rule) {
            $this->add($rule);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function add(BaseRuleInterface $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }
}
