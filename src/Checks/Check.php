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
    protected array $dependencies;

    /**
     * @param string $name
     * @param callable $predicate
     * @param array<string, mixed> $params
     * @param array<string, callable> $calculatedParams
     * @param array<CheckInterface> $dependencies
     */
    public function __construct(
        string $name,
        callable $predicate,
        array $params = [],
        array $calculatedParams = [],
        array $dependencies = []
    ) {
        $this->name = $name;
        $this->predicate = $predicate;
        $this->params = $params;
        $this->calculatedParams = $calculatedParams;
        $this->dependencies = $dependencies;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke($value, array $previousErrors, bool $preventDuplicate = false): void
    {
        if ($preventDuplicate) {
            foreach ($previousErrors as $error) {
                if ($error->getName() === $this->name) {
                    return;
                }
            }
        }

        foreach ($this->dependencies as $check) {
            $check(
                $value,
                $previousErrors,
                true
            );
        }

        $params = $this->params;
        foreach ($this->calculatedParams as $key => $paramGetter) {
            $params[$key] = $paramGetter($value);
        }

        try {
            if (($this->predicate)($value, ...array_values($params)) === false) {
                throw new CheckError($this->name, $value, $params);
            }
        } catch (ValidationError $e) {
            $params[Param::RULE] = $e->getName();
            $params[Param::VIOLATED_RESTRICTIONS] = $e->getViolatedRestrictions();
            throw new CheckError($this->name, $value, $params);
        }
    }
}
