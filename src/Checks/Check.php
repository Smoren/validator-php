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
    protected array $dependsOn;

    /**
     * @param string $name
     * @param callable $predicate
     * @param array<string, mixed> $params
     * @param array<CheckInterface> $dependsOn
     */
    public function __construct(
        string $name,
        callable $predicate,
        array $params = [],
        array $dependsOn = []
    ) {
        $this->name = $name;
        $this->predicate = $predicate;
        $this->params = $params;
        $this->dependsOn = $dependsOn;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($value, array $previousErrors): void
    {
        foreach ($this->dependsOn as $check) {
            $check->execute($value, $previousErrors);
        }

        if (($this->predicate)($value, ...array_values($this->params)) === false) {
            throw new CheckError($this->name, $value, $this->params);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
