<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckName;
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
                $this->assertSame($errors, $e->getViolatedRestrictions());
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
                [['a' => '1a', 'b' => 2], ['a' => false, 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasAttribute('a', Value::numeric()),
                [
                    [
                        CheckName::HAS_ATTRIBUTE,
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
                    [CheckName::HAS_ATTRIBUTE, [Param::ATTRIBUTE => 'a']],
                ],
            ],
            [
                [['a' => '1', 'b' => 2], ['a' => true, 'd', 'b' => null]],
                fn () => Value::container()
                    ->hasOptionalAttribute('a', Value::integer()),
                [
                    [
                        CheckName::HAS_ATTRIBUTE, [
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
