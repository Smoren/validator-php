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
    protected bool $blocking;

    /**
     * @param string $name
     * @param callable $predicate
     * @param array<string, mixed> $params
     * @param bool $isBlocking
     */
    public function __construct(string $name, callable $predicate, array $params = [], bool $isBlocking = false)
    {
        $this->name = $name;
        $this->predicate = $predicate;
        $this->params = $params;
        $this->blocking = $isBlocking;
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
    public function isBlocking(): bool
    {
        return $this->blocking;
    }
}
