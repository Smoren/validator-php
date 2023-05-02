<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Interfaces\CompositeRuleInterface;

abstract class CompositeRule extends MixedRule implements CompositeRuleInterface
{
    /**
     * @var array<RuleInterface>
     */
    protected array $rules = [];

    /**
     * @param array<RuleInterface> $rules
     */
    public function __construct(array $rules, string $name)
    {
        parent::__construct($name);
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function addRule(RuleInterface $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }
}
