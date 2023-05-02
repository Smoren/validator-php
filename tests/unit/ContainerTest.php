<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\Param;
use Smoren\Validator\Structs\RuleName;
use Smoren\Validator\Tests\Unit\Fixture\ArrayAccessListFixture;

class ContainerTest extends Unit
{
    /**
     * @dataProvider dataProviderForSuccess
     * @param array $input
     * @param callable(): RuleInterface $ruleFactory
     * @return void
     */
    public function testSuccess(array $input, callable $ruleFactory): void
    {
        $rule = $ruleFactory();
        foreach ($input as $value) {
            $rule->validate($value);
        }
        $this->assertTrue(true);
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
                    ->hasAttribute('id', Value::integer()->positive())
                    ->hasAttribute('probability', Value::float()->between(0, 1))
                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
                        Value::container()
                            ->array()
                            ->lengthIs(Value::integer()->equal(2))
                            ->allValuesAre(Value::integer())
                    ))
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForFail
     * @param array $input
     * @param callable(): RuleInterface $ruleFactory
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
                $this->assertSame($errors, $e->getSummary());
            }
        }
        $this->assertTrue(true);
    }

    public function dataProviderForFail(): array
    {
        return [
            [
                [1, '2', true, false, 'asd'],
                fn () => Value::container(),
                [
                    [CheckErrorName::NOT_CONTAINER, []],
                ],
            ],
            [
                [[1, 2], [1, 2, 3, 4], []],
                fn () => Value::container()
                    ->lengthIs(Value::integer()->odd()),
                [
                    [
                        CheckErrorName::INVALID_LENGTH,
                        [
                            Param::RULE => RuleName::INTEGER,
                            Param::VIOLATIONS => [[CheckErrorName::NOT_ODD, []]]
                        ]
                    ],
                ],
            ],
            [
                [(object)[1, 2, 3], (object)[1, 2, 3, 4, 5], (object)[1]],
                fn () => Value::container()
                    ->array(),
                [
                    [CheckErrorName::NOT_ARRAY, []],
                ],
            ],
            [
                [[1, 2, 'a' => 3], [1 => 2]],
                fn () => Value::container()
                    ->indexedArray(),
                [
                    [CheckErrorName::NOT_INDEXED_ARRAY, []],
                ],
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                fn () => Value::container()
                    ->object(),
                [
                    [CheckErrorName::NOT_OBJECT, []],
                ],
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->arrayAccessible(),
                [
                    [CheckErrorName::NOT_ARRAY_ACCESSIBLE, []],
                ],
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->countable(),
                [
                    [CheckErrorName::NOT_COUNTABLE, []],
                ],
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->iterable(),
                [
                    [CheckErrorName::NOT_ITERABLE, []],
                ],
            ],
            [
                [[], [1, 2, 3], new ArrayAccessListFixture([]), new ArrayAccessListFixture([1, 2, 3])],
                fn () => Value::container()
                    ->stdObject(),
                [
                    [CheckErrorName::NOT_STD_OBJECT, []],
                ],
            ],
            [
                [[], [1, 2, 3]],
                fn () => Value::container()
                    ->instanceOf(ArrayAccessListFixture::class),
                [
                    [CheckErrorName::NOT_INSTANCE_OF, [Param::GIVEN_TYPE => 'array']],
                ],
            ],
            [
                [(object)[], (object)[1, 2, 3]],
                fn () => Value::container()
                    ->instanceOf(ArrayAccessListFixture::class),
                [
                    [CheckErrorName::NOT_INSTANCE_OF, [Param::GIVEN_TYPE => 'stdClass']],
                ],
            ],
            [
                [[1], ['a' => 1], [1, 2, 3]],
                fn () => Value::container()
                    ->empty(),
                [
                    [CheckErrorName::NOT_EMPTY, []],
                ],
            ],
            [
                [[]],
                fn () => Value::container()
                    ->notEmpty(),
                [
                    [CheckErrorName::EMPTY, []],
                ],
            ],
            [
                [['a' => 1], ['a' => [], 'd']],
                fn () => Value::container()
                    ->hasAttribute('a')
                    ->hasAttribute('b'),
                [
                    [CheckErrorName::ATTRIBUTE_NOT_EXIST, [Param::ATTRIBUTE => 'b']],
                ],
            ],
            [
                [['a' => '1a', 'b' => 2], ['a' => false, 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
                [
                    [
                        CheckErrorName::INVALID_ATTRIBUTE,
                        [
                            Param::ATTRIBUTE => 'a',
                            Param::RULE => RuleName::NUMERIC,
                            Param::VIOLATIONS => [
                                [CheckErrorName::NOT_NUMERIC, []],
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
                    [CheckErrorName::ATTRIBUTE_NOT_EXIST, [Param::ATTRIBUTE => 'a']],
                ],
            ],
            [
                [['a' => '1', 'b' => 2], ['a' => true, 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasOptionalAttribute('a', Value::integer()),
                [
                    [
                        CheckErrorName::INVALID_ATTRIBUTE, [
                            Param::ATTRIBUTE => 'a',
                            Param::RULE => RuleName::INTEGER,
                            Param::VIOLATIONS => [
                                [CheckErrorName::NOT_INTEGER, []],
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
                    [CheckErrorName::SOME_KEYS_INVALID, [
                        Param::RULE => RuleName::NUMERIC,
                        Param::VIOLATIONS => [
                            [CheckErrorName::NOT_NUMERIC, []],
                        ],
                    ]],
                ],
            ],
            [
                [[-1 => 1, 2, 3, 4], [-100 => 5], [1 => 2, -3 => 5]],
                fn () => Value::container()
                    ->allKeysAre(Value::numeric()->nonNegative()),
                [
                    [CheckErrorName::SOME_KEYS_INVALID, [
                        Param::RULE => RuleName::NUMERIC,
                        Param::VIOLATIONS => [
                            [CheckErrorName::NOT_NON_NEGATIVE, []],
                        ],
                    ]],
                ],
            ],
            [
                [[2, 4, 7, 8], [1], [1001, 2000, 8000]],
                fn () => Value::container()
                    ->allValuesAre(Value::integer()->even()),
                [
                    [CheckErrorName::SOME_VALUES_INVALID,
                        [
                            Param::RULE => RuleName::INTEGER,
                            Param::VIOLATIONS => [
                                [CheckErrorName::NOT_EVEN, []],
                            ],
                        ],
                    ],
                ],
            ],
//            [
//                [
//                    [
//                        'id' => '13',
//                        'probability' => 1.92,
//                        'vectors' => [[1, 2.1], [3, 4], [5, 6]],
//                    ],
//                ],
//                fn () => Value::container()
//                    ->hasAttribute('id', Value::integer()->positive())
//                    ->hasAttribute('probability', Value::float()->between(0, 1)->equal(0.5))
//                    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
//                        Value::container()
//                            ->array()
//                            ->lengthIs(Value::integer()->equal(2))
//                            ->allValuesAre(Value::integer())
//                    )),
//                []
//            ],
        ];
    }
}
