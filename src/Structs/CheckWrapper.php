<?php

declare(strict_types=1);

namespace Smoren\Validator\Structs;

use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\CheckWrapperInterface;

final class CheckWrapper implements CheckWrapperInterface
{
    /**
     * @var CheckInterface
     */
    protected CheckInterface $check;
    /**
     * @var bool
     */
    protected bool $isInterrupting;

    /**
     * @param CheckInterface $check
     * @param bool $isInterrupting
     */
    public function __construct(CheckInterface $check, bool $isInterrupting)
    {
        $this->check = $check;
        $this->isInterrupting = $isInterrupting;
    }

    /**
     * {@inheritDoc}
     */
    public function getCheck(): CheckInterface
    {
        return $this->check;
    }

    /**
     * {@inheritDoc}
     */
    public function isInterrupting(): bool
    {
        return $this->isInterrupting;
    }

    /**
     * {@inheritDoc}
     */
    public function setInterrupting(bool $value = true): void
    {
        $this->isInterrupting = $value;
    }
}
