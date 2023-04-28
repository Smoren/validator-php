<?php

namespace Smoren\Validator\Interfaces;

interface ContainerRuleInterface
{
    /**
     * @return static
     */
    public function array(): self;

    /**
     * @return static
     */
    public function indexedArray(): self;

    /**
     * @return static
     */
    public function iterable(): self;

    /**
     * @return static
     */
    public function countable(): self;

    /**
     * @return static
     */
    public function empty(): self;

    /**
     * @return static
     */
    public function notEmpty(): self;

    /**
     * @return static
     */
    public function associativeArray(): self;

    /**
     * @return static
     */
    public function arrayAccessible(): self;

    /**
     * @return static
     */
    public function object(): self;

    /**
     * @return static
     */
    public function stdObject(): self;

    /**
     * @param class-string $class
     *
     * @return static
     */
    public function instanceOf(string $class): self;

    /**
     * @param IntegerRuleInterface $rule
     *
     * @return self
     */
    public function lengthIs(IntegerRuleInterface $rule): self;

    public function hasAttribute(string $attribute, ?BaseRuleInterface $rule = null): self;

    public function everyKeyIs(BaseRuleInterface $rule): self;

    public function everyValueIs(BaseRuleInterface $rule): self;
}
