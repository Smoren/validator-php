<?php

declare(strict_types=1);

namespace Smoren\Validator\Helpers;

final class TypeHelper
{
    /**
     * @param mixed $value
     * @return string
     */
    public static function getType($value): string
    {
        return \is_object($value)
            ? \get_class($value)
            : \gettype($value);
    }
}
