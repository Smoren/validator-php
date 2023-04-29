<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface StringRuleInterface extends RuleInterface
{
    /**
     * @param string|numeric $value
     *
     * @return static
     */
    public function equal($value): self;

    /**
     * @param string $value
     *
     * @return static
     */
    public function same(string $value): self;

    /**
     * @return static
     */
    public function numeric(): self;

    /**
     * @return static
     */
    public function empty(): self;

    /**
     * @return static
     */
    public function notEmpty(): self;

    /**
     * @param string $regex
     *
     * @return static
     */
    public function match(string $regex): self;

    /**
     * @param IntegerRuleInterface $rule
     *
     * @return static
     */
    public function lengthIs(IntegerRuleInterface $rule): self;
}
