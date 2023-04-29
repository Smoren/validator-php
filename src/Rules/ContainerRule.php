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

class ContainerRule extends Rule implements ContainerRuleInterface
{
    public const ERROR_NOT_CONTAINER = 'not_container';
    public const ERROR_NOT_ARRAY = 'not_array';
    public const ERROR_NOT_INDEXED_ARRAY = 'not_array';
    public const ERROR_NOT_ASSOCIATIVE_ARRAY = 'not_array';
    public const ERROR_NOT_ITERABLE = 'not_iterable';
    public const ERROR_NOT_COUNTABLE = 'not_countable';
    public const ERROR_NOT_EMPTY = 'not_empty';
    public const ERROR_EMPTY = 'not_empty';
    public const ERROR_NOT_ARRAY_ACCESSIBLE = 'not_array_accessible';
    public const ERROR_NOT_OBJECT = 'not_object';
    public const ERROR_NOT_STD_OBJECT = 'not_std_object';
    public const ERROR_NOT_INSTANCE_OF = 'not_instance_of';
    public const ERROR_BAD_LENGTH = 'bad_length';
    public const ERROR_ATTRIBUTE_NOT_EXIST = 'attribute_not_exist';
    public const ERROR_BAD_ATTRIBUTE = 'bad_attribute';
    public const ERROR_SOME_KEYS_BAD = 'some_keys_bad';
    public const ERROR_SOME_VALUES_BAD = 'some_values_bad';

    /**
     * ContainerRule constructor.
     */
    public function __construct()
    {
        $this->check(new Check(
            self::ERROR_NOT_CONTAINER,
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
            self::ERROR_NOT_INDEXED_ARRAY,
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
            self::ERROR_NOT_ASSOCIATIVE_ARRAY,
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
            self::ERROR_NOT_ARRAY_ACCESSIBLE,
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
            self::ERROR_NOT_EMPTY,
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
            self::ERROR_EMPTY,
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
            self::ERROR_NOT_OBJECT,
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
            self::ERROR_NOT_STD_OBJECT,
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
            self::ERROR_NOT_INSTANCE_OF,
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
        $violations = [];
        return $this->check(new Check(
            self::ERROR_BAD_LENGTH,
            static function ($value) use ($rule, &$violations) {
                try {
                    /** @var \Countable $value */
                    $rule->validate(\count($value));
                    return true;
                } catch (ValidationError $e) {
                    $violations = $e->getSummary();
                    return false;
                }
            },
            ['violations' => &$violations],
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

        $violations = [];
        return $this->check(new Check(
            self::ERROR_BAD_ATTRIBUTE,
            function ($value) use ($name, $rule, &$violations) {
                try {
                    $rule->validate(ContainerAccessHelper::getAttributeValue($value, $name));
                    return true;
                } catch (ValidationError $e) {
                    $violations = $e->getSummary();
                    return false;
                }
            },
            ['name' => $name, 'violations' => &$violations],
            [$this->getHasAttributeCheck($name)]
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function everyKeyIs(RuleInterface $rule): self
    {
        $violations = [];
        return $this->check(
            new Check(
                self::ERROR_SOME_KEYS_BAD,
                static function ($value) use ($rule, &$violations) {
                    foreach ($value as $k => $v) {
                        try {
                            $rule->validate($k);
                        } catch (ValidationError $e) {
                            $violations = $e->getSummary();
                            return false;
                        }
                    }
                    return true;
                },
                ['violations' => &$violations],
                [$this->getIterableCheck()]
            )
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function everyValueIs(RuleInterface $rule): self
    {
        $violations = [];
        return $this->check(
            new Check(
                self::ERROR_SOME_VALUES_BAD,
                static function ($value) use ($rule, &$violations) {
                    foreach ($value as $v) {
                        try {
                            $rule->validate($v);
                        } catch (ValidationError $e) {
                            $violations = $e->getSummary();
                            return false;
                        }
                    }
                    return true;
                },
                ['violations' => &$violations],
                [$this->getIterableCheck()]
            )
        );
    }

    protected function getArrayCheck(): CheckInterface
    {
        return new Check(
            self::ERROR_NOT_ARRAY,
            fn ($value) => \is_array($value)
        );
    }

    protected function getCountableCheck(): CheckInterface
    {
        return new Check(
            self::ERROR_NOT_COUNTABLE,
            fn ($value) => \is_countable($value)
        );
    }

    protected function getIterableCheck(): CheckInterface
    {
        return new Check(
            self::ERROR_NOT_ITERABLE,
            fn ($value) => \is_iterable($value)
        );
    }

    protected function getHasAttributeCheck(string $name): CheckInterface
    {
        return new Check(
            self::ERROR_ATTRIBUTE_NOT_EXIST,
            fn ($value) => ContainerAccessHelper::hasAccessibleAttribute($value, $name),
            ['name' => $name]
        );
    }
}
