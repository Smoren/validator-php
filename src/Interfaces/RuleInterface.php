<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface RuleInterface extends BaseRuleInterface
{
    /**
     * @param CheckInterface $check
     *
     * @return static
     */
    public function addCheck(CheckInterface $check): self;

    /**
     * @param string $name
     * @param callable $predicate
     * @param array<string, mixed> $params
     * @param bool $isInterrupting
     *
     * @return static
     */
    public function check(string $name, callable $predicate, array $params = [], bool $isInterrupting = false): self;

    /**
     * @return static
     */
    public function stopOnViolation(): self;

    /**
     * @return static
     */
    public function stopOnAnyPriorViolation(): self;
}
