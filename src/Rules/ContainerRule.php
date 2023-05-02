<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Helpers\ContainerAccessHelper;
use Smoren\Validator\Helpers\TypeHelper;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class ContainerRule extends Rule implements ContainerRuleInterface
{
    /**
     * ContainerRule constructor.
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(
            CheckBuilder::create(CheckName::CONTAINER, CheckErrorName::NOT_CONTAINER)
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
    public function array(): self
    {
        return $this->check($this->getArrayCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function indexedArray(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::INDEXED_ARRAY, CheckErrorName::NOT_INDEXED_ARRAY)
                ->withPredicate(fn ($value) => \array_values($value) === $value)
                ->withDependOnChecks([$this->getArrayCheck()])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function associativeArray(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ASSOCIATIVE_ARRAY, CheckErrorName::NOT_ASSOCIATIVE_ARRAY)
                ->withPredicate(fn ($value) => \array_values($value) !== $value)
                ->withDependOnChecks([$this->getArrayCheck()])
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function arrayAccessible(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ARRAY_ACCESSIBLE, CheckErrorName::NOT_ARRAY_ACCESSIBLE)
                ->withPredicate(fn ($value) => \is_array($value) || $value instanceof \ArrayAccess)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function iterable(): self
    {
        return $this->check($this->getIterableCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function countable(): self
    {
        return $this->check($this->getCountableCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function empty(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::EMPTY, CheckErrorName::NOT_EMPTY)
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
            CheckBuilder::create(CheckName::NOT_EMPTY, CheckErrorName::EMPTY)
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
    public function object(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::OBJECT, CheckErrorName::NOT_OBJECT)
                ->withPredicate(fn ($value) => \is_object($value))
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stdObject(): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::STD_OBJECT, CheckErrorName::NOT_STD_OBJECT)
                ->withPredicate(fn ($value) => $value instanceof \stdClass)
                ->build()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function instanceOf(string $class): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::INSTANCE_OF, CheckErrorName::NOT_INSTANCE_OF)
                ->withPredicate(fn ($value) => $value instanceof $class)
                ->withCalculatedParams([Param::GIVEN_TYPE => fn ($value) => TypeHelper::getType($value)])
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
            CheckBuilder::create(CheckName::LENGTH_IS, CheckErrorName::BAD_LENGTH)
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
    public function hasAttribute(string $name, ?RuleInterface $rule = null): self
    {
        if ($rule === null) {
            return $this->check($this->getHasAttributeCheck($name));
        }

        return $this->check(
            CheckBuilder::create(CheckName::HAS_ATTRIBUTE, CheckErrorName::BAD_ATTRIBUTE)
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
    public function hasOptionalAttribute(string $name, RuleInterface $rule): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::HAS_ATTRIBUTE, CheckErrorName::BAD_ATTRIBUTE)
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
    public function allKeysAre(RuleInterface $rule): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ALL_KEYS_ARE, CheckErrorName::SOME_KEYS_BAD)
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
    public function allValuesAre(RuleInterface $rule): self
    {
        return $this->check(
            CheckBuilder::create(CheckName::ALL_VALUES_ARE, CheckErrorName::SOME_VALUES_BAD)
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
        return CheckBuilder::create(CheckName::ARRAY, CheckErrorName::NOT_ARRAY)
            ->withPredicate(fn ($value) => \is_array($value))
            ->build();
    }

    protected function getCountableCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::COUNTABLE, CheckErrorName::NOT_COUNTABLE)
            ->withPredicate(fn ($value) => \is_countable($value))
            ->build();
    }

    protected function getIterableCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::ITERABLE, CheckErrorName::NOT_ITERABLE)
            ->withPredicate(fn ($value) => \is_iterable($value))
            ->build();
    }

    protected function getHasAttributeCheck(string $name): CheckInterface
    {
        return CheckBuilder::create(CheckName::HAS_ATTRIBUTE, CheckErrorName::ATTRIBUTE_NOT_EXIST)
            ->withPredicate(fn ($value) => ContainerAccessHelper::hasAccessibleAttribute($value, $name))
            ->withParams([Param::ATTRIBUTE => $name])
            ->build();
    }
}
