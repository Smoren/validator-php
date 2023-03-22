<?php

namespace Smoren\Validator\Structs;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Interfaces\CheckInterface;

class Check implements CheckInterface
{
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var callable
     */
    protected $predicate;
    /**
     * @var array<string, mixed>
     */
    protected array $params;
    /**
     * @var bool
     */
    protected bool $isInterrupting;

    /**
     * @param string $name
     * @param callable $predicate
     * @param array<string, mixed> $params
     * @param bool $isInterrupting
     */
    public function __construct(string $name, callable $predicate, array $params = [], bool $isInterrupting = false)
    {
        $this->name = $name;
        $this->predicate = $predicate;
        $this->params = $params;
        $this->isInterrupting = $isInterrupting;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($value): void
    {
        if (($this->predicate)($value, ...array_values($this->params)) === false) {
            throw new CheckError($this->name, $value, $this->params);
        }
    }

    /**
     * @return bool
     */
    public function isInterrupting(): bool
    {
        return $this->isInterrupting;
    }

    /**
     * @param bool $value
     *
     * @return static
     */
    public function setInterrupting(bool $value = true): CheckInterface
    {
        $this->isInterrupting = $value;
        return $this;
    }
}
