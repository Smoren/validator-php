<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Helpers\ContainerAccessHelper;
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
        $this->check(new Check(
            CheckName::CONTAINER,
            CheckErrorName::NOT_CONTAINER,
            fn ($value) => \is_array($value) || \is_object($value),
            []
        ), true);
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
        return $this->check(new Check(
            CheckName::INDEXED_ARRAY,
            CheckErrorName::NOT_INDEXED_ARRAY,
            fn ($value) => (\array_values($value) === $value),
            [],
            [$this->getArrayCheck()]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function associativeArray(): self
    {
        return $this->check(new Check(
            CheckName::ASSOCIATIVE_ARRAY,
            CheckErrorName::NOT_ASSOCIATIVE_ARRAY,
            fn ($value) => \array_values($value) !== $value,
            [],
            [$this->getArrayCheck()]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function arrayAccessible(): self
    {
        return $this->check(new Check(
            CheckName::ARRAY_ACCESSIBLE,
            CheckErrorName::NOT_ARRAY_ACCESSIBLE,
            fn ($value) => \is_array($value) || $value instanceof \ArrayAccess
        ));
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
        return $this->check(new Check(
            CheckName::EMPTY,
            CheckErrorName::NOT_EMPTY,
            fn ($value) => \count($value) === 0,
            [],
            [$this->getCountableCheck()]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function notEmpty(): self
    {
        return $this->check(new Check(
            CheckName::NOT_EMPTY,
            CheckErrorName::EMPTY,
            fn ($value) => \count($value) > 0,
            [],
            [$this->getCountableCheck()]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function object(): self
    {
        return $this->check(new Check(
            CheckName::OBJECT,
            CheckErrorName::NOT_OBJECT,
            fn ($value) => \is_object($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stdObject(): self
    {
        return $this->check(new Check(
            CheckName::STD_OBJECT,
            CheckErrorName::NOT_STD_OBJECT,
            fn ($value) => $value instanceof \stdClass
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function instanceOf(string $class): self
    {
        return $this->check(new Check(
            CheckName::INSTANCE_OF,
            CheckErrorName::NOT_INSTANCE_OF,
            fn ($value) => $value instanceof $class
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lengthIs(IntegerRuleInterface $rule): self
    {
        return $this->check(new Check(
            CheckName::LENGTH_IS,
            CheckErrorName::BAD_LENGTH,
            static function ($value) use ($rule) {
                /** @var \Countable $value */
                $rule->validate(\count($value));
                return true;
            },
            [],
            [$this->getCountableCheck()]
        ));
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

        return $this->check(new Check(
            CheckName::HAS_ATTRIBUTE,
            CheckErrorName::BAD_ATTRIBUTE,
            static function ($value, string $name) use ($rule) {
                $rule->validate(ContainerAccessHelper::getAttributeValue($value, $name));
                return true;
            },
            [Param::ATTRIBUTE => $name],
            [$this->getHasAttributeCheck($name)]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function hasOptionalAttribute(string $name, RuleInterface $rule): self
    {
        return $this->check(new Check(
            CheckName::HAS_ATTRIBUTE,
            CheckErrorName::BAD_ATTRIBUTE,
            static function ($value) use ($name, $rule) {
                if (!ContainerAccessHelper::hasAccessibleAttribute($value, $name)) {
                    return true;
                }
                $rule->validate(ContainerAccessHelper::getAttributeValue($value, $name));
                return true;
            },
            [Param::ATTRIBUTE => $name],
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function allKeysAre(RuleInterface $rule): self
    {
        return $this->check(
            new Check(
                CheckName::ALL_KEYS_ARE,
                CheckErrorName::SOME_KEYS_BAD,
                static function ($value) use ($rule) {
                    foreach ($value as $k => $v) {
                        $rule->validate($k);
                    }
                    return true;
                },
                [],
                [$this->getIterableCheck()]
            )
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
            new Check(
                CheckName::ALL_VALUES_ARE,
                CheckErrorName::SOME_VALUES_BAD,
                static function ($value) use ($rule) {
                    foreach ($value as $v) {
                        $rule->validate($v);
                    }
                    return true;
                },
                [],
                [$this->getIterableCheck()]
            )
        );
    }

    protected function getArrayCheck(): CheckInterface
    {
        return new Check(
            CheckName::ARRAY,
            CheckErrorName::NOT_ARRAY,
            fn ($value) => \is_array($value)
        );
    }

    protected function getCountableCheck(): CheckInterface
    {
        return new Check(
            CheckName::COUNTABLE,
            CheckErrorName::NOT_COUNTABLE,
            fn ($value) => \is_countable($value)
        );
    }

    protected function getIterableCheck(): CheckInterface
    {
        return new Check(
            CheckName::ITERABLE,
            CheckErrorName::NOT_ITERABLE,
            fn ($value) => \is_iterable($value)
        );
    }

    protected function getHasAttributeCheck(string $name): CheckInterface
    {
        return new Check(
            CheckName::HAS_ATTRIBUTE,
            CheckErrorName::ATTRIBUTE_NOT_EXIST,
            fn ($value) => ContainerAccessHelper::hasAccessibleAttribute($value, $name),
            [Param::ATTRIBUTE => $name]
        );
    }
}
