<?php

declare(strict_types=1);

namespace Smoren\Validator\Checks;

use Smoren\Validator\Exceptions\CheckError;
use Smoren\Validator\Interfaces\CheckInterface;

final class WrappedCheck implements CheckInterface
{
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var CheckInterface
     */
    protected CheckInterface $check;
    /**
     * @var callable
     */
    protected $errorHandler;

    /**
     * @param string $name
     * @param CheckInterface $check
     * @param callable $errorHandler
     */
    public function __construct(string $name, CheckInterface $check, callable $errorHandler)
    {
        $this->name = $name;
        $this->check = $check;
        $this->errorHandler = $errorHandler;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($value, array $previousErrors, bool $preventDuplicate = false): void
    {
        try {
            $this->check->execute($value, $previousErrors, $preventDuplicate);
        } catch (CheckError $e) {
            throw ($this->errorHandler)($e, $this->name);
        }
    }
}
