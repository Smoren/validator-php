<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Interfaces\CompositeRuleInterface;

abstract class CompositeRule extends MixedRule implements CompositeRuleInterface
{
    /**
     * @var array<MixedRuleInterface>
     */
    protected array $rules = [];

    /**
     * @param array<MixedRuleInterface> $rules
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
    public function addRule(MixedRuleInterface $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }
}
