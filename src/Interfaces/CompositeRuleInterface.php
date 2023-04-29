<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface CompositeRuleInterface extends RuleInterface
{
    /**
     * @param RuleInterface $rule
     *
     * @return static
     */
    public function addRule(RuleInterface $rule): self;
}
