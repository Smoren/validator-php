<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\Checks\ContainerCheckFactory;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;

class ContainerRule extends MixedRule implements ContainerRuleInterface
{
    /**
     * ContainerRule constructor.
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->check(ContainerCheckFactory::getNumericCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function array(bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getArrayCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function indexedArray(bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getIndexedArrayCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function associativeArray(bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getAssociativeArrayCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function arrayAccessible(bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getArrayAccessibleCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function iterable(bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getIterableCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function countable(bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getCountableCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function object(bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getObjectCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stdObject(bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getStdObjectCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function instanceOf(string $class, bool $stopOnViolation = true): self
    {
        return $this->check(ContainerCheckFactory::getInstanceOfCheck($class), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function empty(): self
    {
        return $this->check(ContainerCheckFactory::getEmptyCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function notEmpty(): self
    {
        return $this->check(ContainerCheckFactory::getNotEmptyCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lengthIs(IntegerRuleInterface $rule): self
    {
        return $this->check(ContainerCheckFactory::getLengthIsCheck($rule));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function hasAttribute(string $name, ?MixedRuleInterface $rule = null): self
    {
        if ($rule === null) {
            return $this->check(ContainerCheckFactory::getHasAttributeCheck($name));
        }

        return $this->check(ContainerCheckFactory::getAttributeIsCheck($name, $rule));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function hasOptionalAttribute(string $name, MixedRuleInterface $rule): self
    {
        return $this->check(ContainerCheckFactory::getHasOptionalAttributeCheck($name, $rule));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function hasIndex(int $index, ?MixedRuleInterface $rule = null): self
    {
        if ($rule === null) {
            return $this->check(ContainerCheckFactory::getHasIndexCheck($index));
        }

        return $this->check(ContainerCheckFactory::getValueByIndexCheck($index, $rule));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function allKeysAre(MixedRuleInterface $rule): self
    {
        return $this->check(ContainerCheckFactory::getAllKeysAreCheck($rule));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function allValuesAre(MixedRuleInterface $rule): self
    {
        return $this->check(ContainerCheckFactory::getAllValuesAreCheck($rule));
    }
}
