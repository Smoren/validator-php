<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\BaseRuleInterface;
use Smoren\Validator\Interfaces\CompositeRuleInterface;

abstract class CompositeRule extends Rule implements CompositeRuleInterface
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
            $this->addRule($rule);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function addRule(BaseRuleInterface $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }
}
