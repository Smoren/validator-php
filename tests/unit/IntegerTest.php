<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Structs\CheckErrorName;

class IntegerTest extends Unit
{
    /**
     * @dataProvider dataProviderForSuccess
     * @param array $input
     * @param IntegerRuleInterface $rule
     * @return void
     */
    public function testSuccess(array $input, IntegerRuleInterface $rule): void
    {
        foreach ($input as $value) {
            $rule->validate($value);
        }
        $this->assertTrue(true);
    }

    public function dataProviderForSuccess(): array
    {
        return [
            [
                [1, 2, 3, -1, -2, -3],
                Value::integer(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3],
                Value::integer()
                    ->nullable(),
            ],
            [
                [1, 2, 3, 10, 150],
                Value::integer()
                    ->positive(),
            ],
            [
                [-1, -2, -3, -10, -150],
                Value::integer()
                    ->negative(),
            ],
            [
                [-1, -2, -3, -0, -150],
                Value::integer()
                    ->nonPositive(),
            ],
            [
                [0, 1, 2, 3, 10, 150],
                Value::integer()
                    ->nonNegative(),
            ],
            [
                [6, 7, 8, 10, 150],
                Value::integer()
                    ->greaterTran(5),
            ],
            [
                [5, 6, 7, 8, 10, 150],
                Value::integer()
                    ->greaterOrEqual(5),
            ],
            [
                [4, 3, 2, 1, 0, -100],
                Value::integer()
                    ->lessTran(5),
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                Value::integer()
                    ->lessOrEqual(5),
            ],
            [
                [6, 8],
                Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
            ],
            [
                [7, 9],
                Value::integer()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [null, 7, 9],
                Value::integer()
                    ->nullable()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [6, 8, 10],
                Value::integer()
                    ->positive()
                    ->even()
                    ->between(5, 10),
            ],
            [
                [5, 7, 9],
                Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
            ],
            [
                [5, 7, 9, null],
                Value::integer()
                    ->positive()
                    ->nullable()
                    ->odd()
                    ->between(5, 10),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFail
     * @param array $input
     * @param IntegerRuleInterface $rule
     * @param array $errors
     * @return void
     */
    public function testFail(array $input, IntegerRuleInterface $rule, array $errors): void
    {
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
                [null],
                Value::integer(),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                Value::integer(),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                Value::integer()
                    ->nullable(),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                Value::integer()
                    ->nullable()
                    ->positive(),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                ],
            ],
            [
                [0, -1, -2, -3, -10, -150],
                Value::integer()
                    ->positive(),
                [
                    [CheckErrorName::NOT_POSITIVE, []],
                ],
            ],
            [
                [0, 1, 3, 10, 150],
                Value::integer()
                    ->negative(),
                [
                    [CheckErrorName::NOT_NEGATIVE, []],
                ],
            ],
            [
                [1, 2, 3, 10, 150],
                Value::integer()
                    ->nonPositive(),
                [
                    [CheckErrorName::NOT_NON_POSITIVE, []],
                ],
            ],
            [
                [-1, -2, -3, -10, -150],
                Value::integer()
                    ->nonNegative(),
                [
                    [CheckErrorName::NOT_NON_NEGATIVE, []],
                ],
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                Value::integer()
                    ->greaterTran(5),
                [
                    [CheckErrorName::NOT_GREATER, ['number' => 5]],
                ],
            ],
            [
                [4, 3, 2, 1, 0, -100],
                Value::integer()
                    ->greaterOrEqual(5),
                [
                    [CheckErrorName::NOT_GREATER_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [5, 6, 7, 8, 10, 150],
                Value::integer()
                    ->lessTran(5),
                [
                    [CheckErrorName::NOT_LESS, ['number' => 5]],
                ],
            ],
            [
                [6, 7, 8, 10, 150],
                Value::integer()
                    ->lessOrEqual(5),
                [
                    [CheckErrorName::NOT_LESS_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [1, 3, 11, 13],
                Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_EVEN, []],
                    [CheckErrorName::NOT_IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, -11, -13],
                Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_POSITIVE, []],
                    [CheckErrorName::NOT_EVEN, []],
                    [CheckErrorName::NOT_IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, -11, -13],
                Value::integer()
                    ->positive()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_POSITIVE, []],
                    [CheckErrorName::NOT_EVEN, []],
                ],
            ],
            [
                [-1, -3, -11, -13],
                Value::integer()
                    ->positive()
                    ->stopOnViolation()
                    ->nonNegative()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_POSITIVE, []],
                ],
            ],
            [
                [1, 3, 5, 7, 9],
                Value::integer()
                    ->positive()
                    ->stopOnViolation()
                    ->nonNegative()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_EVEN, []],
                ],
            ],
            [
                [-1, -3, -11, -13],
                Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10)
                    ->stopOnAnyPriorViolation(),
                [
                    [CheckErrorName::NOT_POSITIVE, []],
                ],
            ],
            [
                [7, 9],
                Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_EVEN, []],
                ],
            ],
            [
                [6, 8],
                Value::integer()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_ODD, []],
                ],
            ],
            [
                [6, 8],
                Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckErrorName::NOT_ODD, []],
                ],
            ],
            [
                [1, 3, 11, 13],
                Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckErrorName::NOT_BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckErrorName::NOT_ODD, []],
                    [CheckErrorName::NOT_BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                Value::integer()
                    ->positive()
                    ->stopOnAnyPriorViolation()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckErrorName::NOT_ODD, []],
                    [CheckErrorName::NOT_BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [6, 8, 10],
                Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckErrorName::NOT_ODD, []],
                ],
            ],
        ];
    }
}
