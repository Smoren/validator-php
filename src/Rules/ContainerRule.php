<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Helpers\ContainerAccessHelper;
use Smoren\Validator\Helpers\TypeHelper;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class ContainerRule extends MixedRule implements ContainerRuleInterface
{
    /**
     * ContainerRule constructor.
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(
            CheckBuilder::create(CheckName::CONTAINER)
                ->withPredicate(fn ($value) => \is_array($value) || \is_object($value))
                ->build(),
            true
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function array(bool $stopOnViolation = true): self
    {
        return $this->check($this->getArrayCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function indexedArray(bool $stopOnViolation = true): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::INDEXED_ARRAY)
                ->withPredicate(fn ($value) => \array_values($value) === $value)
                ->withDependOnChecks([$this->getArrayCheck()])
                ->build(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function associativeArray(bool $stopOnViolation = true): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ASSOCIATIVE_ARRAY)
                ->withPredicate(fn ($value) => \array_values($value) !== $value)
                ->withDependOnChecks([$this->getArrayCheck()])
                ->build(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function arrayAccessible(bool $stopOnViolation = true): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ARRAY_ACCESSIBLE)
                ->withPredicate(fn ($value) => \is_array($value) || $value instanceof \ArrayAccess)
                ->build(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function iterable(bool $stopOnViolation = true): self
    {
        return $this->check($this->getIterableCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function countable(bool $stopOnViolation = true): self
    {
        return $this->check($this->getCountableCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function object(bool $stopOnViolation = true): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::OBJECT)
                ->withPredicate(fn ($value) => \is_object($value))
                ->build(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stdObject(bool $stopOnViolation = true): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::STD_OBJECT)
                ->withPredicate(fn ($value) => $value instanceof \stdClass)
                ->build(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function instanceOf(string $class, bool $stopOnViolation = true): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::INSTANCE_OF)
                ->withPredicate(fn ($value) => $value instanceof $class)
                ->withCalculatedParams([Param::GIVEN_TYPE => fn ($value) => TypeHelper::getType($value)])
                ->build(),
            $stopOnViolation
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function empty(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::EMPTY)
                ->withPredicate(fn ($value) => \count($value) === 0)
                ->withDependOnChecks([$this->getCountableCheck()])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function notEmpty(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::NOT_EMPTY)
                ->withPredicate(fn ($value) => \count($value) > 0)
                ->withDependOnChecks([$this->getCountableCheck()])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lengthIs(IntegerRuleInterface $rule): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::LENGTH_IS)
                ->withPredicate(static function ($value) use ($rule) {
                    /** @var \Countable $value */
                    $rule->validate(\count($value));
                    return true;
                })
                ->withDependOnChecks([$this->getCountableCheck()])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function hasAttribute(string $name, ?MixedRuleInterface $rule = null): self
    {
        if ($rule === null) {
            return $this->check($this->getHasAttributeCheck($name));
        }

        return $this->check(
            CheckBuilder::create(CheckName::ATTRIBUTE_IS)
                ->withPredicate(static function ($value, string $name) use ($rule) {
                    $rule->validate(ContainerAccessHelper::getAttributeValue($value, $name));
                    return true;
                })
                ->withParams([Param::ATTRIBUTE => $name])
                ->withDependOnChecks([$this->getHasAttributeCheck($name)])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function hasOptionalAttribute(string $name, MixedRuleInterface $rule): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::HAS_ATTRIBUTE)
                ->withPredicate(static function ($value) use ($name, $rule) {
                    if (!ContainerAccessHelper::hasAccessibleAttribute($value, $name)) {
                        return true;
                    }
                    $rule->validate(ContainerAccessHelper::getAttributeValue($value, $name));
                    return true;
                })
                ->withParams([Param::ATTRIBUTE => $name])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function allKeysAre(MixedRuleInterface $rule): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ALL_KEYS_ARE)
                ->withPredicate(static function ($value) use ($rule) {
                    foreach ($value as $k => $v) {
                        $rule->validate($k);
                    }
                    return true;
                })
                ->withDependOnChecks([$this->getIterableCheck()])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function allValuesAre(MixedRuleInterface $rule): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ALL_VALUES_ARE)
                ->withPredicate(static function ($value) use ($rule) {
                    foreach ($value as $v) {
                        $rule->validate($v);
                    }
                    return true;
                })
                ->withDependOnChecks([$this->getIterableCheck()])
                ->build()
        );
    }

    protected function getArrayCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::ARRAY)
            ->withPredicate(fn ($value) => \is_array($value))
            ->build();
    }

    protected function getCountableCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::COUNTABLE)
            ->withPredicate(fn ($value) => \is_countable($value))
            ->build();
    }

    protected function getIterableCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::ITERABLE)
            ->withPredicate(fn ($value) => \is_iterable($value))
            ->build();
    }

    protected function getHasAttributeCheck(string $name): CheckInterface
    {
        return CheckBuilder::create(CheckName::HAS_ATTRIBUTE)
            ->withPredicate(fn ($value) => ContainerAccessHelper::hasAccessibleAttribute($value, $name))
            ->withParams([Param::ATTRIBUTE => $name])
            ->build();
    }
}
