<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckErrorName;

class IntegerTest extends Unit
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
                [1, 2, 3, -1, -2, -3],
                fn () => Value::integer(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3],
                fn () => Value::integer()
                    ->nullable(),
            ],
            [
                [0, -0],
                fn () => Value::integer()
                    ->falsy(),
            ],
            [
                [1, -1, 2, -2, 3],
                fn () => Value::integer()
                    ->truthy(),
            ],
            [
                [1, 2, 3, 10, 150],
                fn () => Value::integer()
                    ->positive(),
            ],
            [
                [-1, -2, -3, -10, -150],
                fn () => Value::integer()
                    ->negative(),
            ],
            [
                [-1, -2, -3, -0, -150],
                fn () => Value::integer()
                    ->nonPositive(),
            ],
            [
                [0, 1, 2, 3, 10, 150],
                fn () => Value::integer()
                    ->nonNegative(),
            ],
            [
                [6, 7, 8, 10, 150],
                fn () => Value::integer()
                    ->greaterTran(5),
            ],
            [
                [5, 6, 7, 8, 10, 150],
                fn () => Value::integer()
                    ->greaterOrEqual(5),
            ],
            [
                [4, 3, 2, 1, 0, -100],
                fn () => Value::integer()
                    ->lessTran(5),
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                fn () => Value::integer()
                    ->lessOrEqual(5),
            ],
            [
                [6, 8],
                fn () => Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
            ],
            [
                [7, 9],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [null, 7, 9],
                fn () => Value::integer()
                    ->nullable()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [6, 8, 10],
                fn () => Value::integer()
                    ->positive()
                    ->even()
                    ->between(5, 10),
            ],
            [
                [5, 7, 9],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
            ],
            [
                [5, 7, 9, null],
                fn () => Value::integer()
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
                [null],
                fn () => Value::integer(),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                fn () => Value::integer(),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                fn () => Value::integer()
                    ->nullable(),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                ],
            ],
            [
                [1, -1, 2, -2, 3],
                fn () => Value::integer()
                    ->falsy(),
                [
                    [CheckErrorName::NOT_FALSY, []],
                ],
            ],
            [
                [0, -0],
                fn () => Value::integer()
                    ->truthy(),
                [
                    [CheckErrorName::NOT_TRUTHY, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                fn () => Value::integer()
                    ->nullable()
                    ->positive(),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                ],
            ],
            [
                [0, -1, -2, -3, -10, -150],
                fn () => Value::integer()
                    ->positive(),
                [
                    [CheckErrorName::NOT_POSITIVE, []],
                ],
            ],
            [
                [0, 1, 3, 10, 150],
                fn () => Value::integer()
                    ->negative(),
                [
                    [CheckErrorName::NOT_NEGATIVE, []],
                ],
            ],
            [
                [1, 2, 3, 10, 150],
                fn () => Value::integer()
                    ->nonPositive(),
                [
                    [CheckErrorName::NOT_NON_POSITIVE, []],
                ],
            ],
            [
                [-1, -2, -3, -10, -150],
                fn () => Value::integer()
                    ->nonNegative(),
                [
                    [CheckErrorName::NOT_NON_NEGATIVE, []],
                ],
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                fn () => Value::integer()
                    ->greaterTran(5),
                [
                    [CheckErrorName::NOT_GREATER, ['number' => 5]],
                ],
            ],
            [
                [4, 3, 2, 1, 0, -100],
                fn () => Value::integer()
                    ->greaterOrEqual(5),
                [
                    [CheckErrorName::NOT_GREATER_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [5, 6, 7, 8, 10, 150],
                fn () => Value::integer()
                    ->lessTran(5),
                [
                    [CheckErrorName::NOT_LESS, ['number' => 5]],
                ],
            ],
            [
                [6, 7, 8, 10, 150],
                fn () => Value::integer()
                    ->lessOrEqual(5),
                [
                    [CheckErrorName::NOT_LESS_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [1, 3, 11, 13],
                fn () => Value::integer()
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
                fn () => Value::integer()
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
                fn () => Value::integer()
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
                fn () => Value::integer()
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
                fn () => Value::integer()
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
                fn () => Value::integer()
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
                fn () => Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_EVEN, []],
                ],
            ],
            [
                [6, 8],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
                [
                    [CheckErrorName::NOT_ODD, []],
                ],
            ],
            [
                [6, 8],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckErrorName::NOT_ODD, []],
                ],
            ],
            [
                [1, 3, 11, 13],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckErrorName::NOT_BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                fn () => Value::integer()
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
                fn () => Value::integer()
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
                fn () => Value::integer()
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
