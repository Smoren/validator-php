<?php

namespace Smoren\Validator\Structs;

class CheckName
{
    public const NOT_NULL = 'not_null';
    public const BOOLEAN = 'bool';
    public const TRUTHY = 'truthy';
    public const FALSY = 'falsy';
    public const EQUAL = 'equal';
    public const SAME = 'same';

    public const NUMERIC = 'numeric';
    public const NUMBER = 'number';
    public const STRING = 'string';
    public const POSITIVE = 'positive';
    public const NON_POSITIVE = 'non_positive';
    public const NON_NEGATIVE = 'non_negative';
    public const NEGATIVE = 'negative';
    public const GREATER = 'greater';
    public const GREATER_OR_EQUEAL = 'greater_or_equal';
    public const LESS = 'less';
    public const LESS_OR_EQUEAL = 'less_or_equal';
    public const BETWEEN = 'in_segment';
    public const IN_INTERVAL = 'in_interval';

    public const INTEGER = 'integer';
    public const EVEN = 'even';
    public const ODD = 'odd';

    public const FLOAT = 'float';
    public const NON_FRACTIONAL = 'not_fractional';
    public const FRACTIONAL = 'fractional';
    public const INFINITE = 'infinite';
    public const FINITE = 'finite';
    public const NAN = 'nan';
    public const NOT_NAN = 'not_nan';

    public const CONTAINER = 'container';
    public const ARRAY = 'array';
    public const INDEXED_ARRAY = 'indexed_array';
    public const ASSOCIATIVE_ARRAY = 'associative_array';
    public const ITERABLE = 'iterable';
    public const COUNTABLE = 'countable';
    public const EMPTY = 'not_empty';
    public const NOT_EMPTY = 'not_empty';
    public const ARRAY_ACCESSIBLE = 'array_accessible';
    public const OBJECT = 'object';
    public const STD_OBJECT = 'std_object';
    public const INSTANCE_OF = 'instance_of';
    public const LENGTH_IS = 'length_is';
    public const HAS_ATTRIBUTE = 'has_attribute';
    public const ATTRIBUTE_IS = 'attribute_is';
    public const ALL_KEYS_ARE = 'all_keys_are';
    public const ALL_VALUES_ARE = 'all_values_are';

    public const MATCH = 'match';
    public const HAS_SUBSTRING = 'has_substring';
    public const STARTS_WITH = 'starts_with';
    public const ENDS_WITH = 'ends_with';
}
