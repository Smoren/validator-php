<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\NumberRule;
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
                new IntegerRule(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3],
                (new IntegerRule())
                    ->nullable(),
            ],
            [
                [1, 2, 3, 10, 150],
                (new IntegerRule())
                    ->positive(),
            ],
            [
                [-1, -2, -3, -10, -150],
                (new IntegerRule())
                    ->negative(),
            ],
            [
                [-1, -2, -3, -0, -150],
                (new IntegerRule())
                    ->nonPositive(),
            ],
            [
                [0, 1, 2, 3, 10, 150],
                (new IntegerRule())
                    ->nonNegative(),
            ],
            [
                [6, 7, 8, 10, 150],
                (new IntegerRule())
                    ->greaterTran(5),
            ],
            [
                [5, 6, 7, 8, 10, 150],
                (new IntegerRule())
                    ->greaterOrEqual(5),
            ],
            [
                [4, 3, 2, 1, 0, -100],
                (new IntegerRule())
                    ->lessTran(5),
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                (new IntegerRule())
                    ->lessOrEqual(5),
            ],
            [
                [6, 8],
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
            ],
            [
                [7, 9],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [null, 7, 9],
                (new IntegerRule())
                    ->nullable()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [6, 8, 10],
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->between(5, 10),
            ],
            [
                [5, 7, 9],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->between(5, 10),
            ],
            [
                [5, 7, 9, null],
                (new IntegerRule())
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
                $this->assertEquals($value, $e->getValue());
                $this->assertEquals($errors, $e->getSummary());
            }
        }
        $this->assertTrue(true);
    }

    public function dataProviderForFail(): array
    {
        return [
            [
                [null],
                new IntegerRule(),
                [
                    [Rule::ERROR_NULL, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                new IntegerRule(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                (new IntegerRule())
                    ->nullable(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                (new IntegerRule())
                    ->nullable()
                    ->positive(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                ],
            ],
            [
                [0, -1, -2, -3, -10, -150],
                (new IntegerRule())
                    ->positive(),
                [
                    [NumberRule::ERROR_NOT_POSITIVE, []],
                ],
            ],
            [
                [0, 1, 3, 10, 150],
                (new IntegerRule())
                    ->negative(),
                [
                    [NumberRule::ERROR_NOT_NEGATIVE, []],
                ],
            ],
            [
                [1, 2, 3, 10, 150],
                (new IntegerRule())
                    ->nonPositive(),
                [
                    [NumberRule::ERROR_NOT_NON_POSITIVE, []],
                ],
            ],
            [
                [-1, -2, -3, -10, -150],
                (new IntegerRule())
                    ->nonNegative(),
                [
                    [NumberRule::ERROR_NOT_NON_NEGATIVE, []],
                ],
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                (new IntegerRule())
                    ->greaterTran(5),
                [
                    [NumberRule::ERROR_NOT_GREATER, ['number' => 5]],
                ],
            ],
            [
                [4, 3, 2, 1, 0, -100],
                (new IntegerRule())
                    ->greaterOrEqual(5),
                [
                    [NumberRule::ERROR_NOT_GREATER_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [5, 6, 7, 8, 10, 150],
                (new IntegerRule())
                    ->lessTran(5),
                [
                    [NumberRule::ERROR_NOT_LESS, ['number' => 5]],
                ],
            ],
            [
                [6, 7, 8, 10, 150],
                (new IntegerRule())
                    ->lessOrEqual(5),
                [
                    [NumberRule::ERROR_NOT_LESS_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [1, 3, 11, 13],
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [IntegerRule::ERROR_NOT_EVEN, []],
                    [NumberRule::ERROR_NOT_IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, -11, -13],
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [NumberRule::ERROR_NOT_POSITIVE, []],
                    [IntegerRule::ERROR_NOT_EVEN, []],
                    [NumberRule::ERROR_NOT_IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, -11, -13],
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [NumberRule::ERROR_NOT_POSITIVE, []],
                    [IntegerRule::ERROR_NOT_EVEN, []],
                ],
            ],
            [
                [-1, -3, -11, -13],
                (new IntegerRule())
                    ->positive()
                    ->stopOnViolation()
                    ->nonNegative()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [NumberRule::ERROR_NOT_POSITIVE, []],
                ],
            ],
            [
                [1, 3, 5, 7, 9],
                (new IntegerRule())
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
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->inInterval(5, 10)
                    ->stopOnAnyPriorViolation(),
                [
                    [NumberRule::ERROR_NOT_POSITIVE, []],
                ],
            ],
            [
                [7, 9],
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [IntegerRule::ERROR_NOT_EVEN, []],
                ],
            ],
            [
                [6, 8],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                ],
            ],
            [
                [6, 8],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                ],
            ],
            [
                [1, 3, 11, 13],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [NumberRule::ERROR_NOT_IN_SEGMENT, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                    [NumberRule::ERROR_NOT_IN_SEGMENT, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                (new IntegerRule())
                    ->positive()
                    ->stopOnAnyPriorViolation()
                    ->odd()
                    ->between(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                    [NumberRule::ERROR_NOT_IN_SEGMENT, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [6, 8, 10],
                (new IntegerRule())
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
