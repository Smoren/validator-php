<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use ArrayAccess;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\Check;

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
    public const ERROR_LENGTH_IS_NOT = 'length_is_not';

    /**
     * ContainerRule constructor.
     */
    public function __construct()
    {
        $this->addCheck(new Check(
            self::ERROR_NOT_CONTAINER,
            fn ($value) => is_array($value) || is_object($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function array(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_ARRAY,
            fn ($value) => is_array($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function indexedArray(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_INDEXED_ARRAY,
            fn ($value) => is_array($value) && (array_values($value) === $value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function associativeArray(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_ASSOCIATIVE_ARRAY,
            fn ($value) => is_array($value) && (array_values($value) !== $value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function arrayAccessible(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_ARRAY_ACCESSIBLE,
            fn ($value) => is_array($value) || $value instanceof ArrayAccess
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function iterable(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_ITERABLE,
            fn ($value) => is_iterable($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function countable(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_COUNTABLE,
            fn ($value) => is_countable($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function empty(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_EMPTY,
            fn ($value) => is_countable($value) && count($value) === 0
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function notEmpty(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_EMPTY,
            fn ($value) => is_countable($value) && count($value) > 0
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function object(): self
    {
        return $this->addCheck(new Check(
            self::ERROR_NOT_OBJECT,
            fn ($value) => is_object($value)
        ));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stdObject(): self
    {
        return $this->addCheck(new Check(
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
        return $this->addCheck(new Check(
            self::ERROR_NOT_INSTANCE_OF,
            fn ($value) => $value instanceof $class
        ));
    }

    public function lengthIs(IntegerRuleInterface $rule): ContainerRuleInterface
    {
        $violations = [];
        return $this->addCheck(new Check(
            self::ERROR_LENGTH_IS_NOT,
            static function ($value) use ($rule, &$violations) {
                try {
                    (new self())->countable()->validate($value);
                    /** @var \Countable $value */
                    $rule->validate(count($value));
                    return true;
                } catch (ValidationError $e) {
                    $violations = $e->getSummary();
                    return false;
                }
            },
            ['violations' => &$violations]
        ));
    }
}
