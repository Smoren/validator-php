<?php

namespace Smoren\Validator\Structs;

class CheckErrorName
{
    public const NULL = 'null';
    public const NOT_TRUTHY = 'not_truthy';
    public const NOT_FALSY = 'not_falsy';
    public const NOT_EQUEAL = 'not_equal';
    public const NOT_SAME = 'not_same';

    public const NOT_NUMERIC = 'not_numeric';
    public const NOT_NUMBER = 'not_number';
    public const NOT_STRING = 'not_string';
    public const NOT_POSITIVE = 'not_positive';
    public const NOT_NON_POSITIVE = 'not_non_positive';
    public const NOT_NON_NEGATIVE = 'not_non_negative';
    public const NOT_NEGATIVE = 'not_negative';
    public const NOT_GREATER = 'not_greater';
    public const NOT_GREATER_OR_EQUEAL = 'not_greater_or_equal';
    public const NOT_LESS = 'not_less';
    public const NOT_LESS_OR_EQUEAL = 'not_less_or_equal';
    public const NOT_BETWEEN = 'not_between';
    public const NOT_IN_INTERVAL = 'not_in_interval';

    public const NOT_INTEGER = 'not_integer';
    public const NOT_EVEN = 'not_even';
    public const NOT_ODD = 'not_odd';

    public const NOT_FLOAT = 'not_float';
    public const FRACTIONAL = 'fractional';
    public const NOT_FRACTIONAL = 'not_fractional';
    public const NOT_INFINITE = 'not_infinite';
    public const NOT_FINITE = 'not_finite';

    public const NOT_CONTAINER = 'not_container';
    public const NOT_ARRAY = 'not_array';
    public const NOT_INDEXED_ARRAY = 'not_indexed_array';
    public const NOT_ASSOCIATIVE_ARRAY = 'not_associative_array';
    public const NOT_ITERABLE = 'not_iterable';
    public const NOT_COUNTABLE = 'not_countable';
    public const NOT_EMPTY = 'not_empty';
    public const EMPTY = 'not_empty';
    public const NOT_ARRAY_ACCESSIBLE = 'not_array_accessible';
    public const NOT_OBJECT = 'not_object';
    public const NOT_STD_OBJECT = 'not_std_object';
    public const NOT_INSTANCE_OF = 'not_instance_of';
    public const BAD_LENGTH = 'bad_length';
    public const ATTRIBUTE_NOT_EXIST = 'attribute_not_exist';
    public const BAD_ATTRIBUTE = 'bad_attribute';
    public const SOME_KEYS_BAD = 'some_keys_bad';
    public const SOME_VALUES_BAD = 'some_values_bad';

    public const NOT_MATCH = 'not_match';
    public const HAS_NOT_SUBSTRING = 'has_not_substring';
    public const NOT_STARTS_WITH = 'not_starts_with';
}
