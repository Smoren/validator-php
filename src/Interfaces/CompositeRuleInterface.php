<?php

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\ValidationError;

interface CompositeRuleInterface extends BaseRuleInterface
{
    /**
     * @param BaseRuleInterface $rule
     *
     * @return static
     */
    public function add(BaseRuleInterface $rule): self;
}
