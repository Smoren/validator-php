<?php

declare(strict_types=1);

namespace Smoren\Validator\Structs;

class RuleName
{
    public const DEFAULT = 'default';
    public const NUMERIC = 'numeric';
    public const INTEGER = 'integer';
    public const FLOAT = 'float';
    public const BOOLEAN = 'bool';
    public const STRING = 'string';
    public const CONTAINER = 'container';
    public const OR = 'or';
    public const AND = 'and';
}
