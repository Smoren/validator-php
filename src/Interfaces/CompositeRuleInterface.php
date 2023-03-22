<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface CompositeRuleInterface extends BaseRuleInterface
{
    /**
     * @param BaseRuleInterface $rule
     *
     * @return static
     */
    public function addRule(BaseRuleInterface $rule): self;
}
