<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Structs\CheckName;

interface ContainerRuleInterface extends MixedRuleInterface
{
    /**
     * Checks if the value is an array.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ARRAY
     *
     * @return static
     */
    public function array(): self;

    /**
     * Checks if the value is an indexed array (all indexes ∈ [0, N-1], where N is array length).
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::INDEXED_ARRAY
     * - @see CheckName::ARRAY
     *
     * @return static
     */
    public function indexedArray(): self;

    /**
     * Checks if the value is ab associative array (not indexed).
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ASSOCIATIVE_ARRAY
     * - @see CheckName::ARRAY
     *
     * @return static
     */
    public function associativeArray(): self;

    /**
     * Checks if the value is iterable.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ITERABLE
     *
     * @return static
     */
    public function iterable(): self;

    /**
     * Checks if the value is countable.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::COUNTABLE
     *
     * @return static
     */
    public function countable(): self;

    /**
     * Checks if the value is an array or an ArrayAccess instance.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::ARRAY_ACCESSIBLE
     *
     * @return static
     */
    public function arrayAccessible(): self;

    /**
     * Checks if the value is an object.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::OBJECT
     *
     * @return static
     */
    public function object(): self;

    /**
     * Checks if the value is an instance of stdClass.
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
     *
     * Names of rules that can be violated:
     * - @see CheckName::STD_OBJECT
     *
     * @return static
     */
    public function stdObject(): self;

    /**
     * Checks if the value is instance of some class.
     *
     * @param class-string $class
     *
     * Subsequent checks do not run by default when any violation of this check occurs.
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
     * Checks given rule for the length of the container.
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
     * Checks if the container has attribute with given name and checks optional rule for its value.
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
     * Checks if the container has optional attribute with given name and checks rule for its value if attribute exists.
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
     * Checks if the container has given index and checks optional rule for its value.
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
     * Checks if all keys of the container match the given rule.
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
     * Checks if all values of the container match the given rule.
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
