<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Structs\Param;

class Check implements CheckInterface
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
    protected array $params;
    /**
     * @var array<string, callable>
     */
    protected array $calculatedParams;
    /**
     * @var array<CheckInterface>
     */
    protected array $dependsOnChecks;

    /**
     * @param string $name
     * @param string $errorName
     * @param callable $predicate
     * @param array<string, mixed> $params
     * @param array<CheckInterface> $dependsOnChecks
     */
    public function __construct(
        string $name,
        string $errorName,
        callable $predicate,
        array $params = [],
        array $calculatedParams = [],
        array $dependsOnChecks = []
    ) {
        $this->name = $name;
        $this->errorName = $errorName;
        $this->predicate = $predicate;
        $this->params = $params;
        $this->calculatedParams = $calculatedParams;
        $this->dependsOnChecks = $dependsOnChecks;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($value, array $previousErrors): void
    {
        foreach ($this->dependsOnChecks as $check) {
            $check->execute($value, $previousErrors);
        }

        $params = $this->params;
        foreach ($this->calculatedParams as $key => $paramGetter) {
            $params[$key] = $paramGetter($value);
        }

        try {
            if (($this->predicate)($value, ...array_values($params)) === false) {
                throw new CheckError($this->errorName, $value, $params);
            }
        } catch (ValidationError $e) {
            $params[Param::RULE] = $e->getName();
            $params[Param::VIOLATIONS] = $e->getSummary();
            throw new CheckError($this->errorName, $value, $params);
        }
    }
}
