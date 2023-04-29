<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface RuleInterface extends BaseRuleInterface
{
    /**
     * @param CheckInterface $check
     * @param bool $isInterrupting
     *
     * @return static
     */
    public function check(CheckInterface $check, bool $isInterrupting = false): self;

    /**
     * @return static
     */
    public function stopOnViolation(): self;

    /**
     * @return static
     */
    public function stopOnAnyPriorViolation(): self;
}
