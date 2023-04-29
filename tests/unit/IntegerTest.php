<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Validate;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\NumericRule;
use Smoren\Validator\Rules\Rule;

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
                Validate::integer(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3],
                Validate::integer()
                    ->nullable(),
            ],
            [
                [1, 2, 3, 10, 150],
                Validate::integer()
                    ->positive(),
            ],
            [
                [-1, -2, -3, -10, -150],
                Validate::integer()
                    ->negative(),
            ],
            [
                [-1, -2, -3, -0, -150],
                Validate::integer()
                    ->nonPositive(),
            ],
            [
                [0, 1, 2, 3, 10, 150],
                Validate::integer()
                    ->nonNegative(),
            ],
            [
                [6, 7, 8, 10, 150],
                Validate::integer()
                    ->greaterTran(5),
            ],
            [
                [5, 6, 7, 8, 10, 150],
                Validate::integer()
                    ->greaterOrEqual(5),
            ],
            [
                [4, 3, 2, 1, 0, -100],
                Validate::integer()
                    ->lessTran(5),
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                Validate::integer()
                    ->lessOrEqual(5),
            ],
            [
                [6, 8],
                Validate::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
            ],
            [
                [7, 9],
                Validate::integer()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [null, 7, 9],
                Validate::integer()
                    ->nullable()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [6, 8, 10],
                Validate::integer()
                    ->positive()
                    ->even()
                    ->between(5, 10),
            ],
            [
                [5, 7, 9],
                Validate::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
            ],
            [
                [5, 7, 9, null],
                Validate::integer()
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
                Validate::integer(),
                [
                    [Rule::ERROR_NULL, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                Validate::integer(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                Validate::integer()
                    ->nullable(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                Validate::integer()
                    ->nullable()
                    ->positive(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                ],
            ],
            [
                [0, -1, -2, -3, -10, -150],
                Validate::integer()
                    ->positive(),
                [
                    [NumericRule::ERROR_NOT_POSITIVE, []],
                ],
            ],
            [
                [0, 1, 3, 10, 150],
                Validate::integer()
                    ->negative(),
                [
                    [NumericRule::ERROR_NOT_NEGATIVE, []],
                ],
            ],
            [
                [1, 2, 3, 10, 150],
                Validate::integer()
                    ->nonPositive(),
                [
                    [NumericRule::ERROR_NOT_NON_POSITIVE, []],
                ],
            ],
            [
                [-1, -2, -3, -10, -150],
                Validate::integer()
                    ->nonNegative(),
                [
                    [NumericRule::ERROR_NOT_NON_NEGATIVE, []],
                ],
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                Validate::integer()
                    ->greaterTran(5),
                [
                    [NumericRule::ERROR_NOT_GREATER, ['number' => 5]],
                ],
            ],
            [
                [4, 3, 2, 1, 0, -100],
                Validate::integer()
                    ->greaterOrEqual(5),
                [
                    [NumericRule::ERROR_NOT_GREATER_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [5, 6, 7, 8, 10, 150],
                Validate::integer()
                    ->lessTran(5),
                [
                    [NumericRule::ERROR_NOT_LESS, ['number' => 5]],
                ],
            ],
            [
                [6, 7, 8, 10, 150],
                Validate::integer()
                    ->lessOrEqual(5),
                [
                    [NumericRule::ERROR_NOT_LESS_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [1, 3, 11, 13],
                Validate::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [IntegerRule::ERROR_NOT_EVEN, []],
                    [NumericRule::ERROR_NOT_IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, -11, -13],
                Validate::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [NumericRule::ERROR_NOT_POSITIVE, []],
                    [IntegerRule::ERROR_NOT_EVEN, []],
                    [NumericRule::ERROR_NOT_IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, -11, -13],
                Validate::integer()
                    ->positive()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [NumericRule::ERROR_NOT_POSITIVE, []],
                    [IntegerRule::ERROR_NOT_EVEN, []],
                ],
            ],
            [
                [-1, -3, -11, -13],
                Validate::integer()
                    ->positive()
                    ->stopOnViolation()
                    ->nonNegative()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [NumericRule::ERROR_NOT_POSITIVE, []],
                ],
            ],
            [
                [1, 3, 5, 7, 9],
                Validate::integer()
                    ->positive()
                    ->stopOnViolation()
                    ->nonNegative()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [IntegerRule::ERROR_NOT_EVEN, []],
                ],
            ],
            [
                [-1, -3, -11, -13],
                Validate::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10)
                    ->stopOnAnyPriorViolation(),
                [
                    [NumericRule::ERROR_NOT_POSITIVE, []],
                ],
            ],
            [
                [7, 9],
                Validate::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [IntegerRule::ERROR_NOT_EVEN, []],
                ],
            ],
            [
                [6, 8],
                Validate::integer()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                ],
            ],
            [
                [6, 8],
                Validate::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                ],
            ],
            [
                [1, 3, 11, 13],
                Validate::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [NumericRule::ERROR_NOT_IN_SEGMENT, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                Validate::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                    [NumericRule::ERROR_NOT_IN_SEGMENT, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                Validate::integer()
                    ->positive()
                    ->stopOnAnyPriorViolation()
                    ->odd()
                    ->between(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                    [NumericRule::ERROR_NOT_IN_SEGMENT, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [6, 8, 10],
                Validate::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                ],
            ],
        ];
    }
}
