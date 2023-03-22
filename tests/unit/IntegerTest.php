<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Rules\IntegerRule;

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
                [6, 8, 10],
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->inSegment(5, 10),
            ],
            [
                [5, 7, 9],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->inSegment(5, 10),
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
                ['1', 'a', true, false, null, []],
                new IntegerRule(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                ],
            ],
            [
                [0, -1, -2, -3, -10, -150],
                (new IntegerRule())
                    ->positive(),
                [
                    [IntegerRule::ERROR_NOT_POSITIVE, []],
                ],
            ],
            [
                [0, 1, 3, 10, 150],
                (new IntegerRule())
                    ->negative(),
                [
                    [IntegerRule::ERROR_NOT_NEGATIVE, []],
                ],
            ],
            [
                [1, 2, 3, 10, 150],
                (new IntegerRule())
                    ->nonPositive(),
                [
                    [IntegerRule::ERROR_NOT_NON_POSITIVE, []],
                ],
            ],
            [
                [-1, -2, -3, -10, -150],
                (new IntegerRule())
                    ->nonNegative(),
                [
                    [IntegerRule::ERROR_NOT_NON_NEGATIVE, []],
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
                    [IntegerRule::ERROR_NOT_IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, -11, -13],
                (new IntegerRule())
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [IntegerRule::ERROR_NOT_POSITIVE, []],
                    [IntegerRule::ERROR_NOT_EVEN, []],
                    [IntegerRule::ERROR_NOT_IN_INTERVAL, ['start' => 5, 'end' => 10]],
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
                    ->inSegment(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                ],
            ],
            [
                [1, 3, 11, 13],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->inSegment(5, 10),
                [
                    [IntegerRule::ERROR_NOT_IN_SEGMENT, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->inSegment(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                    [IntegerRule::ERROR_NOT_IN_SEGMENT, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [6, 8, 10],
                (new IntegerRule())
                    ->positive()
                    ->odd()
                    ->inSegment(5, 10),
                [
                    [IntegerRule::ERROR_NOT_ODD, []],
                ],
            ],
        ];
    }
}
