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
    public function array(): self
    {
        return $this->check(ContainerCheckFactory::getArrayCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function indexedArray(): self
    {
        return $this->check(ContainerCheckFactory::getIndexedArrayCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function associativeArray(): self
    {
        return $this->check(ContainerCheckFactory::getAssociativeArrayCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function arrayAccessible(): self
    {
        return $this->check(ContainerCheckFactory::getArrayAccessibleCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function iterable(): self
    {
        return $this->check(ContainerCheckFactory::getIterableCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function countable(): self
    {
        return $this->check(ContainerCheckFactory::getCountableCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function object(): self
    {
        return $this->check(ContainerCheckFactory::getObjectCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function stdObject(): self
    {
        return $this->check(ContainerCheckFactory::getStdObjectCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function instanceOf(string $class): self
    {
        return $this->check(ContainerCheckFactory::getInstanceOfCheck($class), true);
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
