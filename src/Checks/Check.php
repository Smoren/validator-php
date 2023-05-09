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

        $error = null;

        try {
            if (($this->predicate)($value, ...array_values($params)) === false) {
                $error = new CheckError($this->name, $value, $params);
            }
        } catch (ValidationError $e) {
            $params[Param::RULE] = $e->getName();
            $params[Param::VIOLATED_RESTRICTIONS] = $e->getViolatedRestrictions();
            $error = new CheckError($this->name, $value, $params);
        }

        if ($error !== null && (!$preventDuplicate || !$this->isDuplicate($error, $previousErrors))) {
            throw $error;
        }
    }

    /**
     * @param CheckError $error
     * @param array<CheckError> $previousErrors
     *
     * @return bool
     */
    protected function isDuplicate(CheckError $error, array $previousErrors): bool
    {
        foreach ($previousErrors as $previousError) {
            if ($error->equalTo($previousError)) {
                return true;
            }
        }

        return false;
    }
}
