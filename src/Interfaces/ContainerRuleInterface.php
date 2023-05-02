<?php

namespace Smoren\Validator\Interfaces;

interface ContainerRuleInterface extends MixedRuleInterface
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
     * @return static
     */
    public function lengthIs(IntegerRuleInterface $rule): self;

    /**
     * @param string $name
     * @param MixedRuleInterface|null $rule
     *
     * @return static
     */
    public function hasAttribute(string $name, ?MixedRuleInterface $rule = null): self;

    /**
     * @param string $name
     * @param MixedRuleInterface $rule
     *
     * @return static
     */
    public function hasOptionalAttribute(string $name, MixedRuleInterface $rule): self;

    /**
     * @param MixedRuleInterface $rule
     *
     * @return static
     */
    public function allKeysAre(MixedRuleInterface $rule): self;

    /**
     * @param MixedRuleInterface $rule
     *
     * @return static
     */
    public function allValuesAre(MixedRuleInterface $rule): self;
}
