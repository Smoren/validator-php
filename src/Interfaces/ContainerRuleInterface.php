<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Structs\CheckName;

interface ContainerRuleInterface extends MixedRuleInterface
{
    /**
     * Checks if value is array.
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ARRAY
     *
     * @return static
     */
    public function array(): self;

    /**
     * Checks if value is indexed array (all indexes ∈ [0, N-1], where N is array length).
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::INDEXED_ARRAY
     * - @see CheckName::ARRAY
     *
     * @return static
     */
    public function indexedArray(): self;

    /**
     * Checks if value is associative array (not indexed).
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ASSOCIATIVE_ARRAY
     * - @see CheckName::ARRAY
     *
     * @return static
     */
    public function associativeArray(): self;

    /**
     * Checks if value is iterable.
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ITERABLE
     *
     * @return static
     */
    public function iterable(): self;

    /**
     * Checks if value is countable.
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::COUNTABLE
     *
     * @return static
     */
    public function countable(): self;

    /**
     * Checks if value is array or ArrayAccess instance.
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ARRAY_ACCESSIBLE
     *
     * @return static
     */
    public function arrayAccessible(): self;

    /**
     * Checks if value is object.
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::OBJECT
     *
     * @return static
     */
    public function object(): self;

    /**
     * Checks if value is instance of stdClass.
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::STD_OBJECT
     *
     * @return static
     */
    public function stdObject(): self;

    /**
     * Checks if value is instance of some class.
     *
     * @param class-string $class
     *
     * This check stops next checks on violation by default.
     *
     * Names of rules that can be violated:
     * - @see CheckName::OBJECT
     *
     * @return static
     */
    public function instanceOf(string $class): self;

    /**
     * Checks if container is empty (length = 0).
     *
     * Names of rules that can be violated:
     * - @see CheckName::EMPTY
     * - @see CheckName::COUNTABLE
     *
     * @return static
     */
    public function empty(): self;

    /**
     * Checks if container is not empty (length > 0).
     *
     * Names of rules that can be violated:
     * - @see CheckName::NOT_EMPTY
     * - @see CheckName::COUNTABLE
     *
     * @return static
     */
    public function notEmpty(): self;

    /**
     * Checks given rule for the length of container.
     *
     * @param IntegerRuleInterface $rule
     *
     * Names of rules that can be violated:
     * - @see CheckName::LENGTH_IS
     * - @see CheckName::COUNTABLE
     *
     * @return static
     */
    public function lengthIs(IntegerRuleInterface $rule): self;

    /**
     * Checks if container has attribute with given name and checks optional rule for its value.
     *
     * @param string $name
     * @param MixedRuleInterface|null $rule
     *
     * Names of rules that can be violated:
     * - @see CheckName::HAS_ATTRIBUTE
     * - @see CheckName::ATTRIBUTE_IS
     *
     * @return static
     */
    public function hasAttribute(string $name, ?MixedRuleInterface $rule = null): self;

    /**
     * Checks if container has optional attribute with given name and checks rule for its value if attribute exists.
     *
     * @param string $name
     * @param MixedRuleInterface $rule
     *
     * Names of rules that can be violated:
     * - @see CheckName::ATTRIBUTE_IS
     *
     * @return static
     */
    public function hasOptionalAttribute(string $name, MixedRuleInterface $rule): self;

    /**
     * Checks if container has given index and checks optional rule for its value.
     *
     * @param int $index
     * @param MixedRuleInterface|null $rule
     *
     * Names of rules that can be violated:
     * - @see CheckName::HAS_INDEX
     * - @see CheckName::VALUE_BY_INDEX_IS
     *
     * @return static
     */
    public function hasIndex(int $index, ?MixedRuleInterface $rule = null): self;

    /**
     * Checks if all keys of container are match the given rule.
     *
     * @param MixedRuleInterface $rule
     *
     * Names of rules that can be violated:
     * - @see CheckName::ALL_KEYS_ARE
     * - @see CheckName::ITERABLE
     *
     * @return static
     */
    public function allKeysAre(MixedRuleInterface $rule): self;

    /**
     * Checks if all values of container are match the given rule.
     *
     * @param MixedRuleInterface $rule
     *
     * Names of rules that can be violated:
     * - @see CheckName::ALL_VALUES_ARE
     * - @see CheckName::ITERABLE
     *
     * @return static
     */
    public function allValuesAre(MixedRuleInterface $rule): self;
}
