<?php

namespace Smoren\Validator\Factories;

use Smoren\Validator\Checks\Check;
use Smoren\Validator\Interfaces\CheckInterface;

class CheckBuilder
{
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var string
     */
    protected string $errorName;
    /**
     * @var callable
     */
    protected $predicate;
    /**
     * @var array<string, mixed>
     */
    protected array $params = [];
    /**
     * @var array<string, callable>
     */
    protected array $calculatedParams = [];
    /**
     * @var array<CheckInterface>
     */
    protected array $dependsOnChecks = [];

    /**
     * @param string $name
     * @param string $errorName
     * @return self
     */
    public static function create(string $name, string $errorName): self
    {
        return new self($name, $errorName);
    }

    /**
     * @return CheckInterface
     */
    public function build(): CheckInterface
    {
        return new Check(
            $this->name,
            $this->errorName,
            $this->predicate,
            $this->params,
            $this->calculatedParams,
            $this->dependsOnChecks
        );
    }

    /**
     * @param callable $predicate
     * @return $this
     */
    public function withPredicate(callable $predicate): self
    {
        $this->predicate = $predicate;
        return $this;
    }

    /**
     * @param array<string, mixed> $params
     * @return $this
     */
    public function withParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param array<string, callable> $calculatedParams
     * @return $this
     */
    public function withCalculatedParams(array $calculatedParams): self
    {
        $this->calculatedParams = $calculatedParams;
        return $this;
    }

    /**
     * @param array<CheckInterface> $dependsOnChecks
     * @return $this
     */
    public function withDependOnChecks(array $dependsOnChecks): self
    {
        $this->dependsOnChecks = $dependsOnChecks;
        return $this;
    }

    /**
     * @param string $name
     * @param string $errorName
     */
    private function __construct(string $name, string $errorName)
    {
        $this->name = $name;
        $this->errorName = $errorName;
    }
}
