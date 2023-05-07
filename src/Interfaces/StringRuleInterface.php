<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface StringRuleInterface extends MixedRuleInterface
{
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
     * @param string $substr
     *
     * @return static
     */
    public function hasSubstring(string $substr): self;

    /**
     * @param string $substr
     *
     * @return static
     */
    public function startsWith(string $substr): self;

    /**
     * @param string $substr
     *
     * @return static
     */
    public function endsWith(string $substr): self;

    /**
     * @param IntegerRuleInterface $rule
     *
     * @return static
     */
    public function lengthIs(IntegerRuleInterface $rule): self;
}
