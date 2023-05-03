<?php

namespace Smoren\Validator\Interfaces;

interface ContainerRuleInterface extends MixedRuleInterface
{
    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function array(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function indexedArray(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function iterable(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function countable(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function associativeArray(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function arrayAccessible(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function object(bool $stopOnViolation = true): self;

    /**
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function stdObject(bool $stopOnViolation = true): self;

    /**
     * @param string $class
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function instanceOf(string $class, bool $stopOnViolation = true): self;

    /**
     * @return static
     */
    public function empty(): self;

    /**
     * @return static
     */
    public function notEmpty(): self;

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
