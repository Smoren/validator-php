<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\ValidationError;

interface MixedRuleInterface
{
    /**
     * @param mixed $value
     *
     * @return void
     *
     * @throws ValidationError
     */
    public function validate($value): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value): bool;

    /**
     * @return static
     */
    public function nullable(): self;

    /**
     * @return static
     */
    public function truthy(): self;

    /**
     * @return static
     */
    public function falsy(): self;

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function equal($value): self;

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function same($value): self;

    /**
     * @param CheckInterface $check
     * @param bool $stopOnViolation
     *
     * @return static
     */
    public function check(CheckInterface $check, bool $stopOnViolation = false): self;

    /**
     * @return static
     */
    public function stopOnViolation(): self;

    /**
     * @return static
     */
    public function dontStopOnViolation(): self;

    /**
     * @return static
     */
    public function stopOnAnyPriorViolation(): self;

    /**
     * @return static
     */
    public function dontStopOnAllPriorViolations(): self;
}
