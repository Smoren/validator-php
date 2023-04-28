<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

interface CheckWrapperInterface
{
    /**
     * @return CheckInterface
     */
    public function getCheck(): CheckInterface;

    /**
     * @return bool
     */
    public function isInterrupting(): bool;

    /**
     * @param bool $value
     *
     * @return void
     */
    public function setInterrupting(bool $value = true): void;
}
