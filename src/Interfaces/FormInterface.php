<?php

declare(strict_types=1);

namespace Smoren\Validator\Interfaces;

use Smoren\Validator\Exceptions\FormValidationError;

interface FormInterface
{
    /**
     * @param array<string, mixed> $source
     *
     * @return self
     */
    public static function create(array $source): self;

    /**
     * @return void
     *
     * @throws FormValidationError
     */
    public function validate(): void;

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array;

    /**
     * @param array<string, mixed> $source
     */
    public function setAttributes(array $source): void;
}
