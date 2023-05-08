<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;
use Smoren\Validator\Structs\RuleName;
use Smoren\Validator\Tests\Unit\Fixture\ArrayAccessListFixture;
use Smoren\Validator\Tests\Unit\Fixture\ArrayAccessMapFixture;
use Smoren\Validator\Tests\Unit\Fixture\SomeClassFixture;

class ContainerTest extends Unit
{
    /**
     * @dataProvider dataProviderForSuccess
     * @param array $input
     * @param callable(): MixedRuleInterface $ruleFactory
     * @return void
     */
    public function testSuccess(array $input, callable $ruleFactory): void
    {
        $rule = $ruleFactory();
        foreach ($input as $value) {
            $rule->validate($value);
        }
        $this->expectNotToPerformAssertions();
    }

    public function dataProviderForSuccess(): array
    {
        return [
            [
                [[], (object)[], [1, 2, 3], (object)['a' => 1], ['a' => 1]],
                fn () => Value::container(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                fn () => Value::container()
                    ->array(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1], []],
                fn () => Value::container()
                    ->indexedArray(),
            ],
            [
                [[1, 2, 'a' => 3], [1 => 2], ['test' => true, 'a' => []]],
                fn () => Value::container()
                    ->associativeArray(),
            ],
            [
                [(object)[1, 2, 3], (object)[1, 2, 3, 4, 5], (object)[1]],
                fn () => Value::container()
                    ->object(),
            ],
            [
                [[], [1, 2, 3], new ArrayAccessListFixture([]), new ArrayAccessListFixture([1, 2, 3])],
                fn () => Value::container()
                    ->arrayAccessible(),
            ],
            [
                [[], [1, 2, 3], new ArrayAccessListFixture([]), new ArrayAccessListFixture([1, 2, 3])],
                fn () => Value::container()
                    ->countable(),
            ],
            [
                [[], [1, 2, 3], new ArrayAccessListFixture([]), new ArrayAccessListFixture([1, 2, 3])],
                fn () => Value::container()
                    ->iterable(),
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->stdObject(),
            ],
            [
                [new ArrayAccessListFixture([]), new ArrayAccessListFixture([1, 2, 3])],
                fn () => Value::container()
                    ->instanceOf(ArrayAccessListFixture::class),
            ],
            [
                [[]],
                fn () => Value::container()
                    ->empty(),
            ],
            [
                [[1], ['a' => 1], [1, 2, 3]],
                fn () => Value::container()
                    ->notEmpty(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                fn () => Value::container()
                    ->lengthIs(Value::integer()->odd()),
            ],
            [
                [['a' => 1, 'b' => 2], ['a' => [], 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a')
                    ->hasAttribute('b'),
            ],
            [
                [['a' => 1, 'b' => 2], ['a' => '1.23', 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
            ],
            [
                [(object)['a' => 1, 'b' => 2], ['a' => '1.23', 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
            ],
            [
                [new ArrayAccessMapFixture(['a' => 1, 'b' => 2]), new ArrayAccessMapFixture(['a' => '1.23', 'd', 'b' => null])],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
            ],
            [
                [new SomeClassFixture()],
                fn () => Value::container()
                    ->hasAttribute('public', Value::string()),
            ],
            [
                [[1, 2, 3], [1, 2], [1 => 3], [1 => '1.1', 2 => '2.1'], new ArrayAccessListFixture([1, 2, 3])],
                fn () => Value::container()
                    ->hasIndex(1),
            ],
            [
                [[1, 2, 3], [1, 2], [1 => 3], [1 => '1.1', 2 => '2.1'], new ArrayAccessListFixture([1, 2, 3])],
                fn () => Value::container()
                    ->hasIndex(1, Value::numeric()->positive()),
            ],
            [
                [['a' => 1, 'b' => 2], ['a' => 2, 'd', 'b' => null], [], ['b' => 123]],
                fn () => Value::container()
                    ->hasOptionalAttribute('a', Value::integer()),
            ],
            [
                [[2, 4, 6, 8], [4], [1000, 2000, 8000], []],
                fn () => Value::container()
                    ->allValuesAre(Value::integer()->even()),
            ],
            [
                [[1, 2, 3, 4], [5], [1 => 2, 3 => 5], []],
                fn () => Value::container()
                    ->allKeysAre(Value::numeric()->nonNegative()),
            ],
            [
                [
                    [
                        'id' => 13,
                        'probability' => 0.92,
                        'vectors' => [[1, 2], [3, 4], [5, 6]],
                    ],
                ],
                fn () => Value::container()
                    ->array()
                    ->hasAttribute('id', Value::integer()->positive())
                    ->hasAttribute('probability', Value::float()->between(0, 1))
                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
                        Value::container()
                            ->array()
                            ->lengthIs(Value::integer()->equal(2))
                            ->allValuesAre(Value::integer())
                    ))
            ],
            [
                [
                    (object)[
                        'id' => 13,
                        'probability' => 0.92,
                        'vectors' => [[1, 2], [3, 4], [5, 6]],
                    ],
                ],
                fn () => Value::container()
                    ->stdObject()
                    ->hasAttribute('id', Value::integer()->positive())
                    ->hasAttribute('probability', Value::float()->between(0, 1))
                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
                        Value::container()
                            ->array()
                            ->lengthIs(Value::integer()->equal(2))
                            ->allValuesAre(Value::integer())
                    ))
            ],
            [
                [
                    new class () {
                        public $id = 13;
                        public $probability = 0.92;
                        public $vectors = [[1, 2], [3, 4], [5, 6]];
                    },
                ],
                fn () => Value::container()
                    ->hasAttribute('id', Value::integer()->positive())
                    ->hasAttribute('probability', Value::float()->between(0, 1))
                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
                        Value::container()
                            ->array()
                            ->lengthIs(Value::integer()->equal(2))
                            ->allValuesAre(Value::integer())
                    ))
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFail
     * @param array $input
     * @param callable(): MixedRuleInterface $ruleFactory
     * @param array $errors
     * @return void
     */
    public function testFail(array $input, callable $ruleFactory, array $errors): void
    {
        $rule = $ruleFactory();
        foreach ($input as $value) {
            try {
                $rule->validate($value);
                $this->fail();
            } catch (ValidationError $e) {
                $this->assertSame($value, $e->getValue());
                $this->assertSame($errors, $e->getViolatedRestrictions());
            }
        }
        $this->expectNotToPerformAssertions();
    }

    public function dataProviderForFail(): array
    {
        return [
            [
                [1, '2', true, false, 'asd'],
                fn () => Value::container(),
                [
                    [CheckName::CONTAINER, []],
                ],
            ],
            [
                [[1, 2], [1, 2, 3, 4], []],
                fn () => Value::container()
                    ->lengthIs(Value::integer()->odd()),
                [
                    [
                        CheckName::LENGTH_IS,
                        [
                            Param::RULE => RuleName::INTEGER,
                            Param::VIOLATED_RESTRICTIONS => [[CheckName::ODD, []]]
                        ]
                    ],
                ],
            ],
            [
                [(object)[1, 2, 3], (object)[1, 2, 3, 4, 5], (object)[1]],
                fn () => Value::container()
                    ->array(),
                [
                    [CheckName::ARRAY, []],
                ],
            ],
            [
                [[1, 2, 'a' => 3], [1 => 2]],
                fn () => Value::container()
                    ->indexedArray(),
                [
                    [CheckName::INDEXED_ARRAY, []],
                ],
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                fn () => Value::container()
                    ->object(),
                [
                    [CheckName::OBJECT, []],
                ],
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->arrayAccessible(),
                [
                    [CheckName::ARRAY_ACCESSIBLE, []],
                ],
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->countable(),
                [
                    [CheckName::COUNTABLE, []],
                ],
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->iterable(),
                [
                    [CheckName::ITERABLE, []],
                ],
            ],
            [
                [[], [1, 2, 3], new ArrayAccessListFixture([]), new ArrayAccessListFixture([1, 2, 3])],
                fn () => Value::container()
                    ->stdObject(),
                [
                    [CheckName::STD_OBJECT, []],
                ],
            ],
            [
                [[], [1, 2, 3]],
                fn () => Value::container()
                    ->instanceOf(ArrayAccessListFixture::class),
                [
                    [CheckName::INSTANCE_OF, [Param::GIVEN_TYPE => 'array']],
                ],
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->instanceOf(ArrayAccessListFixture::class),
                [
                    [CheckName::INSTANCE_OF, [Param::GIVEN_TYPE => 'stdClass']],
                ],
            ],
            [
                [[1], ['a' => 1], [1, 2, 3]],
                fn () => Value::container()
                    ->empty(),
                [
                    [CheckName::EMPTY, []],
                ],
            ],
            [
                [[]],
                fn () => Value::container()
                    ->notEmpty(),
                [
                    [CheckName::NOT_EMPTY, []],
                ],
            ],
            [
                [['a' => 1], ['a' => [], 'd']],
                fn () => Value::container()
                    ->hasAttribute('a')
                    ->hasAttribute('b'),
                [
                    [CheckName::HAS_ATTRIBUTE, [Param::ATTRIBUTE => 'b']],
                ],
            ],
            [
                [(object)['b' => 2], ['d', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
                [
                    [CheckName::HAS_ATTRIBUTE, [Param::ATTRIBUTE => 'a']],
                ],
            ],
            [
                [new ArrayAccessMapFixture(['b' => 2]), new ArrayAccessMapFixture(['d', 'b' => null])],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
                [
                    [CheckName::HAS_ATTRIBUTE, [Param::ATTRIBUTE => 'a']],
                ],
            ],
            [
                [new SomeClassFixture()],
                fn () => Value::container()
                    ->hasAttribute('private', Value::string()),
                [
                    [CheckName::HAS_ATTRIBUTE, [Param::ATTRIBUTE => 'private']],
                ],
            ],
            [
                [new SomeClassFixture()],
                fn () => Value::container()
                    ->hasAttribute('protected', Value::string()),
                [
                    [CheckName::HAS_ATTRIBUTE, [Param::ATTRIBUTE => 'protected']],
                ],
            ],
            [
                [new SomeClassFixture()],
                fn () => Value::container()
                    ->hasAttribute('not_exist', Value::string()),
                [
                    [CheckName::HAS_ATTRIBUTE, [Param::ATTRIBUTE => 'not_exist']],
                ],
            ],
            [
                [['a' => '1a', 'b' => 2], ['a' => false, 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
                [
                    [
                        CheckName::ATTRIBUTE_IS,
                        [
                            Param::ATTRIBUTE => 'a',
                            Param::RULE => RuleName::NUMERIC,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::NUMERIC, []],
                            ],
                        ],
                    ],
                ],
            ],
            [
                [['b' => 2], ['d', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
                [
                    [
                        CheckName::HAS_ATTRIBUTE,
                        [Param::ATTRIBUTE => 'a'],
                    ],
                ],
            ],
            [
                [['b' => 2], ['d', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
                [
                    [CheckName::HAS_ATTRIBUTE, [Param::ATTRIBUTE => 'a']],
                ],
            ],
            [
                [['a' => '1', 'b' => 2], ['a' => true, 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasOptionalAttribute('a', Value::integer()),
                [
                    [
                        CheckName::ATTRIBUTE_IS, [
                            Param::ATTRIBUTE => 'a',
                            Param::RULE => RuleName::INTEGER,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::INTEGER, []],
                            ],
                        ],
                    ],
                ],
            ],
            [
                [[1], [], [0 => 3, 2 => 5], [3 => '2.1'], new ArrayAccessListFixture([1])],
                fn () => Value::container()
                    ->hasIndex(1),
                [
                    [CheckName::HAS_INDEX, [Param::INDEX => 1]],
                ],
            ],
            [
                [[1], [], [0 => 3, 2 => 5], [3 => '2.1'], new ArrayAccessListFixture([1])],
                fn () => Value::container()
                    ->hasIndex(1, Value::numeric()->positive()),
                [
                    [CheckName::HAS_INDEX, [Param::INDEX => 1]],
                ],
            ],
            [
                [[1, 'a', 3], [1, 'b'], [1 => 'c'], [1 => 'd', 2 => '2.1'], new ArrayAccessListFixture([1, 'e'])],
                fn () => Value::container()
                    ->hasIndex(1, Value::numeric()->positive()),
                [
                    [
                        CheckName::VALUE_BY_INDEX_IS,
                        [
                            Param::INDEX => 1,
                            Param::RULE => RuleName::NUMERIC,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::NUMERIC, []],
                            ],
                        ],
                    ],
                ],
            ],
            [
                [['a' => 1, 2, 3, 4], ['' => 5], [1 => 2, 'b' => 5]],
                fn () => Value::container()
                    ->allKeysAre(Value::numeric()->nonNegative()),
                [
                    [
                        CheckName::ALL_KEYS_ARE,
                        [
                            Param::RULE => RuleName::NUMERIC,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::NUMERIC, []],
                            ],
                        ]
                    ],
                ],
            ],
            [
                [[-1 => 1, 2, 3, 4], [-100 => 5], [1 => 2, -3 => 5]],
                fn () => Value::container()
                    ->allKeysAre(Value::numeric()->nonNegative()),
                [
                    [
                        CheckName::ALL_KEYS_ARE,
                        [
                            Param::RULE => RuleName::NUMERIC,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::NON_NEGATIVE, []],
                            ],
                        ]
                    ],
                ],
            ],
            [
                [[2, 4, 7, 8], [1], [1001, 2000, 8000]],
                fn () => Value::container()
                    ->allValuesAre(Value::integer()->even()),
                [
                    [
                        CheckName::ALL_VALUES_ARE,
                        [
                            Param::RULE => RuleName::INTEGER,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::EVEN, []],
                            ],
                        ],
                    ],
                ],
            ],
            [
                [
                    [
                        'id' => '13',
                        'probability' => 1.92,
                        'vectors' => [[1, 2.1], [3, 4], [5, 6]],
                    ],
                ],
                fn () => Value::container()
                    ->array()
                    ->hasAttribute('id', Value::integer()->positive())
                    ->hasAttribute('probability', Value::float()->between(0, 1))
                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
                        Value::container()
                            ->array()
                            ->lengthIs(Value::integer()->equal(2))
                            ->allValuesAre(Value::integer())
                    )),
                [
                    [
                        CheckName::ATTRIBUTE_IS,
                        [
                            Param::ATTRIBUTE => 'id',
                            Param::RULE => RuleName::INTEGER,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::INTEGER, []],
                            ],
                        ]
                    ],
                    [
                        CheckName::ATTRIBUTE_IS,
                        [
                            Param::ATTRIBUTE => 'probability',
                            Param::RULE => RuleName::FLOAT,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::BETWEEN, ['start' => 0, 'end' => 1]],
                            ],
                        ],
                    ],
                    [
                        CheckName::ATTRIBUTE_IS,
                        [
                            Param::ATTRIBUTE => 'vectors',
                            Param::RULE => RuleName::CONTAINER,
                            Param::VIOLATED_RESTRICTIONS => [
                                [
                                    CheckName::ALL_VALUES_ARE,
                                    [
                                        Param::RULE => RuleName::CONTAINER,
                                        Param::VIOLATED_RESTRICTIONS => [
                                            [
                                                CheckName::ALL_VALUES_ARE,
                                                [
                                                    Param::RULE => RuleName::INTEGER,
                                                    Param::VIOLATED_RESTRICTIONS => [
                                                        [CheckName::INTEGER, []],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    ],
                ],
            ],
            [
                [
                    [
                        'id' => '13',
                        'probability' => 1.92,
                        'vectors' => [[1, 2.1], [3, 4], [5, 6]],
                    ],
                ],
                fn () => Value::container()
                    ->array()
                    ->hasAttribute('id', Value::integer()->positive())
                    ->hasAttribute('probability', Value::float()->between(0, 1)->equal(0.5))
                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
                        Value::container()
                            ->array()
                            ->lengthIs(Value::integer()->equal(2))
                            ->allValuesAre(Value::integer())
                    )),
                [
                    [
                        CheckName::ATTRIBUTE_IS,
                        [
                            Param::ATTRIBUTE => 'id',
                            Param::RULE => RuleName::INTEGER,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::INTEGER, []],
                            ],
                        ]
                    ],
                    [
                        CheckName::ATTRIBUTE_IS,
                        [
                            Param::ATTRIBUTE => 'probability',
                            Param::RULE => RuleName::FLOAT,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::BETWEEN, ['start' => 0, 'end' => 1]],
                                [CheckName::EQUAL, [Param::EXPECTED => 0.5]],
                            ],
                        ],
                    ],
                    [
                        CheckName::ATTRIBUTE_IS,
                        [
                            Param::ATTRIBUTE => 'vectors',
                            Param::RULE => RuleName::CONTAINER,
                            Param::VIOLATED_RESTRICTIONS => [
                                [
                                    CheckName::ALL_VALUES_ARE,
                                    [
                                        Param::RULE => RuleName::CONTAINER,
                                        Param::VIOLATED_RESTRICTIONS => [
                                            [
                                                CheckName::ALL_VALUES_ARE,
                                                [
                                                    Param::RULE => RuleName::INTEGER,
                                                    Param::VIOLATED_RESTRICTIONS => [
                                                        [CheckName::INTEGER, []],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    ],
                ],
            ],
            [
                [
                    (object)[[
                        'id' => '13',
                        'probability' => 1.92,
                        'vectors' => [[1, 2.1], [3, 4], [5, 6]],
                    ]],
                ],
                fn () => Value::container()
                    ->array()
                    ->hasAttribute('id', Value::integer()->positive())
                    ->hasAttribute('probability', Value::float()->between(0, 1)->equal(0.5))
                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
                        Value::container()
                            ->array()
                            ->lengthIs(Value::integer()->equal(2))
                            ->allValuesAre(Value::integer())
                    )),
                [
                    [CheckName::ARRAY, []],
                ],
            ],
            [
                [
                    (object)[
                        'id' => 13,
                        'probability' => '1.92',
                        'vectors' => [[1, 2], [3, 4], [5, 6]],
                    ],
                ],
                fn () => Value::container()
                    ->array()
                    ->dontStopOnViolation()
                    ->hasAttribute('id', Value::integer()->positive())
                    ->hasAttribute('probability', Value::float()->between(0, 1))
                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
                        Value::container()
                            ->array()
                            ->lengthIs(Value::integer()->equal(2))
                            ->allValuesAre(Value::integer())
                    )),
                [
                    [CheckName::ARRAY, []],
                    [
                        CheckName::ATTRIBUTE_IS,
                        [
                            Param::ATTRIBUTE => 'probability',
                            Param::RULE => RuleName::FLOAT,
                            Param::VIOLATED_RESTRICTIONS => [
                                [CheckName::FLOAT, []],
                            ],
                        ],
                    ],
                ],
            ],
            [
                [(object)[1, 2, 3]],
                fn () => Value::container()
                    ->allKeysAre(Value::numeric())
                    ->allValuesAre(Value::integer()),
                [
                    [CheckName::ITERABLE, []]
                ],
            ],
            [
                ['', true, false, 'abc', 123, 1.0, 0],
                fn () => Value::container()
                    ->allKeysAre(Value::numeric())
                    ->allValuesAre(Value::integer()),
                [
                    [CheckName::CONTAINER, []]
                ],
            ],
        ];
    }
}
