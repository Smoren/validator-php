<?php

declare(strict_types=1);

namespace Smoren\Validator\Rules;

use Smoren\Validator\Factories\Checks\NumericCheckFactory;
use Smoren\Validator\Interfaces\NumericRuleInterface;

class NumericRule extends MixedRule implements NumericRuleInterface
{
    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->check(NumericCheckFactory::getNumericCheck(), true);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function number(bool $stopOnViolation = true): self
    {
        return $this->check(NumericCheckFactory::getNumberCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function string(bool $stopOnViolation = true): self
    {
        return $this->check(NumericCheckFactory::getStringCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function integer(bool $stopOnViolation = true): self
    {
        return $this->check(NumericCheckFactory::getIntegerCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function float(bool $stopOnViolation = true): self
    {
        return $this->check(NumericCheckFactory::getFloatCheck(), $stopOnViolation);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function truthy(): self
    {
        return $this->check(NumericCheckFactory::getTruthyCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function falsy(): self
    {
        return $this->check(NumericCheckFactory::getFalsyCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function positive(): self
    {
        return $this->check(NumericCheckFactory::getPositiveCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonPositive(): self
    {
        return $this->check(NumericCheckFactory::getNonPositiveCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonNegative(): self
    {
        return $this->check(NumericCheckFactory::getNonNegativeCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function negative(): self
    {
        return $this->check(NumericCheckFactory::getNegativeCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function greaterThan($number): NumericRuleInterface
    {
        return $this->check(NumericCheckFactory::getGreaterThanCheck($number));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function greaterOrEqual($number): NumericRuleInterface
    {
        return $this->check(NumericCheckFactory::getGreaterOrEqualCheck($number));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lessThan($number): NumericRuleInterface
    {
        return $this->check(NumericCheckFactory::getLessThanCheck($number));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function lessOrEqual($number): NumericRuleInterface
    {
        return $this->check(NumericCheckFactory::getLessOrEqualCheck($number));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function between($start, $end): self
    {
        return $this->check(NumericCheckFactory::getBetweenCheck($start, $end));
    }

    /**
     * {@inheritDoc}
     */
    public function inOpenInterval($start, $end): self
    {
        return $this->check(NumericCheckFactory::getInOpenIntervalCheck($start, $end));
    }

    /**
     * {@inheritDoc}
     */
    public function inLeftHalfOpenInterval($start, $end): self
    {
        return $this->check(NumericCheckFactory::getInLeftOpenIntervalCheck($start, $end));
    }

    /**
     * {@inheritDoc}
     */
    public function inRightHalfOpenInterval($start, $end): self
    {
        return $this->check(NumericCheckFactory::getInRightOpenIntervalCheck($start, $end));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function fractional(): self
    {
        return $this->check(NumericCheckFactory::getFractionalCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nonFractional(): self
    {
        return $this->check(NumericCheckFactory::getNonFractionalCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function finite(): self
    {
        return $this->check(NumericCheckFactory::getFiniteCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function infinite(): self
    {
        return $this->check(NumericCheckFactory::getInfiniteCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function even(): self
    {
        return $this->check(NumericCheckFactory::getEvenCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function odd(): self
    {
        return $this->check(NumericCheckFactory::getOddCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function nan(): self
    {
        return $this->check(NumericCheckFactory::getNanCheck());
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function notNan(): self
    {
        return $this->check(NumericCheckFactory::getNotNanCheck());
    }
}
