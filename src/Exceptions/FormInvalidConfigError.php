<?php

namespace Smoren\Validator\Exceptions;

class FormInvalidConfigError extends \UnexpectedValueException
{
    /**
     * @var class-string
     */
    protected string $formClass;

    /**
     * @param string $message
     * @param class-string $formClass
     */
    public function __construct(string $message, string $formClass)
    {
        parent::__construct($message);
        $this->formClass = $formClass;
    }

    /**
     * @return class-string
     */
    public function getFormClass(): string
    {
        return $this->formClass;
    }
}
