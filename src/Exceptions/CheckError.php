<?php

declare(strict_types=1);

namespace Smoren\Validator\Exceptions;

class CheckError extends \DomainException
{
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var array<string, mixed>
     */
    protected array $params;

    /**
     * @param string $name
     * @param mixed $value
     * @param array<string, mixed> $params
     */
    public function __construct(string $name, $value, array $params)
    {
        parent::__construct('Check error');
        $this->name = $name;
        $this->value = $value;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
