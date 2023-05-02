<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface CompositeRuleInterface extends MixedRuleInterface
{
    /**
     * @param MixedRuleInterface $rule
     *
     * @return static
     */
    public function addRule(MixedRuleInterface $rule): self;
}
