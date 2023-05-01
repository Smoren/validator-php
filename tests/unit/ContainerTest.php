<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\Param;

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
                [[2, 4, 6, 8], [4], [1000, 2000, 8000], []],
                fn () => Value::container()
                    ->allValuesAre(Value::integer()->even()),
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
                    [CheckErrorName::BAD_LENGTH, [Param::RULE => 'integer', Param::VIOLATIONS => [['not_odd', []]]]],
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
                    [CheckErrorName::BAD_ATTRIBUTE, [Param::ATTRIBUTE => 'a', Param::RULE => 'numeric', Param::VIOLATIONS => [['not_numeric', []]]]],
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
                [[2, 4, 7, 8], [1], [1001, 2000, 8000]],
                fn () => Value::container()
                    ->allValuesAre(Value::integer()->even()),
                [
                    [CheckErrorName::SOME_VALUES_BAD, [Param::RULE => 'integer', Param::VIOLATIONS => [['not_even', []]]]],
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
