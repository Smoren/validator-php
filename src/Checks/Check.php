<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Interfaces\CheckInterface;

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
        array $dependsOnChecks = []
    ) {
        $this->name = $name;
        $this->errorName = $errorName;
        $this->predicate = $predicate;
        $this->params = $params;
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

        if (($this->predicate)($value, ...array_values($this->params)) === false) {
            throw new CheckError($this->errorName, $value, $this->params);
        }
    }
}
