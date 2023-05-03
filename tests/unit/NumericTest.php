<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class NumericTest extends Unit
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
                [1, 2.1, '3', -1, -2, '-3'],
                fn () => Value::numeric(),
            ],
            [
                [null, 1, 2.1, '3', -1, -2, '-3'],
                fn () => Value::numeric()
                    ->nullable(),
            ],
            [
                [1, 2.1, 3, -1, -2, -3],
                fn () => Value::numeric()
                    ->number(),
            ],
            [
                ['1', '2.1', '3', '-1', '-2', '-3'],
                fn () => Value::numeric()
                    ->string(),
            ],
            [
                [0, -0, 0.0, -0.0, '0', '-0', '0.0', '-0.0'],
                fn () => Value::numeric()
                    ->falsy(),
            ],
            [
                [1, 1.1, -1, -1.1, '2', '2.2', '-2', '-2.2', '3'],
                fn () => Value::numeric()
                    ->truthy(),
            ],
            [
                [1, 1.0],
                fn () => Value::numeric()
                    ->equal(1),
            ],
            [
                [1],
                fn () => Value::numeric()
                    ->same(1),
            ],
            [
                [1, 2, '3', 10, '150'],
                fn () => Value::numeric()
                    ->positive(),
            ],
            [
                [-1, -2.1, '-3', -10, '-150'],
                fn () => Value::numeric()
                    ->negative(),
            ],
            [
                [-1, -2.1, '-3', -0, '-150'],
                fn () => Value::numeric()
                    ->nonPositive(),
            ],
            [
                [0, 1, 2.1, '3', 10, '150'],
                fn () => Value::numeric()
                    ->nonNegative(),
            ],
            [
                [6, 7, '8', 10, '150'],
                fn () => Value::numeric()
                    ->greaterThan(5),
            ],
            [
                [5, 6, 7, '8', 10, '150'],
                fn () => Value::numeric()
                    ->greaterOrEqual(5),
            ],
            [
                [4, 3, 2, '1', 0, '-100'],
                fn () => Value::numeric()
                    ->lessThan(5),
            ],
            [
                [5, 4, 3, 2, '1', 0, '-100'],
                fn () => Value::numeric()
                    ->lessOrEqual(5),
            ],
            [
                [6, '8'],
                fn () => Value::numeric()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
            ],
            [
                ['7', 9],
                fn () => Value::numeric()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [null, '7', '9', 7.0],
                fn () => Value::numeric()
                    ->nullable()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
            ],
            [
                [6, '8', '10', 8.0],
                fn () => Value::numeric()
                    ->positive()
                    ->even()
                    ->between(5, 10),
            ],
            [
                [5, '7', '9', 7.0],
                fn () => Value::numeric()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
            ],
            [
                [5, '7', '9', 5.0, null],
                fn () => Value::numeric()
                    ->positive()
                    ->nullable()
                    ->odd()
                    ->between(5, 10),
            ],
            [
                [1.000000001, 2.1, '3.1', '-1.001', -2.1, -3.3],
                fn () => Value::numeric()
                    ->fractional(),
            ],
            [
                [1.0, 2.0, 3, '-1.0', -2.0, '-3.0'],
                fn () => Value::numeric()
                    ->nonFractional(),
            ],
            [
                [1.0, '2.0', 3, '-1.0', -2.0, -3.0, '999999999.0', -999999999.0],
                fn () => Value::numeric()
                    ->finite(),
            ],
            [
                [INF, -INF],
                fn () => Value::numeric()
                    ->infinite(),
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
                [null],
                fn () => Value::numeric(),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::numeric(),
                [
                    [CheckName::NUMERIC, []],
                ],
            ],
            [
                [1, 1.1, -1, -1.1, '2', '2.2', '-2', '-2.2', '3'],
                fn () => Value::numeric()
                    ->falsy(),
                [
                    [CheckName::FALSY, []],
                ],
            ],
            [
                [0, -0, 0.0, -0.0, '0', '-0', '0.0', '-0.0'],
                fn () => Value::numeric()
                    ->truthy(),
                [
                    [CheckName::TRUTHY, []],
                ],
            ],
            [
                [2, 1.1],
                fn () => Value::numeric()
                    ->equal(1),
                [
                    [CheckName::EQUAL, [Param::EXPECTED => 1]],
                ],
            ],
            [
                [1.0],
                fn () => Value::numeric()
                    ->same(1),
                [
                    [CheckName::SAME, [Param::EXPECTED => 1]],
                ],
            ],
            [
                ['1', '2.1', '3', '-1', '-2', '-3'],
                fn () => Value::numeric()
                    ->number(),
                [
                    [CheckName::NUMBER, []],
                ],
            ],
            [
                [1, 2.1, 3, -1, -2, -3],
                fn () => Value::numeric()
                    ->string(),
                [
                    [CheckName::STRING, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::numeric()
                    ->nullable(),
                [
                    [CheckName::NUMERIC, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::numeric()
                    ->nullable()
                    ->positive(),
                [
                    [CheckName::NUMERIC, []],
                ],
            ],
            [
                [0, -1, -2, -3, -10, -150],
                fn () => Value::numeric()
                    ->positive(),
                [
                    [CheckName::POSITIVE, []],
                ],
            ],
            [
                [0, 1, 3, 10, 150],
                fn () => Value::numeric()
                    ->negative(),
                [
                    [CheckName::NEGATIVE, []],
                ],
            ],
            [
                [1, 2, 3, 10, 150],
                fn () => Value::numeric()
                    ->nonPositive(),
                [
                    [CheckName::NON_POSITIVE, []],
                ],
            ],
            [
                [-1, -2, -3, -10, -150],
                fn () => Value::numeric()
                    ->nonNegative(),
                [
                    [CheckName::NON_NEGATIVE, []],
                ],
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                fn () => Value::numeric()
                    ->greaterThan(5),
                [
                    [CheckName::GREATER, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [4, 3, 2, 1, 0, -100],
                fn () => Value::numeric()
                    ->greaterOrEqual(5),
                [
                    [CheckName::GREATER_OR_EQUEAL, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [5, 6, 7, 8, 10, 150],
                fn () => Value::numeric()
                    ->lessThan(5),
                [
                    [CheckName::LESS, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [6, 7, 8, 10, 150],
                fn () => Value::numeric()
                    ->lessOrEqual(5),
                [
                    [CheckName::LESS_OR_EQUEAL, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [1, 3, '11', 11.0, 13],
                fn () => Value::numeric()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckName::EVEN, []],
                    [CheckName::IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, '-11', -11.0, -13],
                fn () => Value::numeric()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckName::POSITIVE, []],
                    [CheckName::EVEN, []],
                    [CheckName::IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, '-11', -11.0, -13],
                fn () => Value::numeric()
                    ->positive()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [CheckName::POSITIVE, []],
                    [CheckName::EVEN, []],
                ],
            ],
            [
                [-1, -3, '-11', -11.0, -13],
                fn () => Value::numeric()
                    ->positive()
                    ->stopOnViolation()
                    ->nonNegative()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [CheckName::POSITIVE, []],
                ],
            ],
            [
                [1, 3, 5.0, '7', 9],
                fn () => Value::numeric()
                    ->positive()
                    ->stopOnViolation()
                    ->nonNegative()
                    ->even()
                    ->stopOnViolation()
                    ->inInterval(5, 10),
                [
                    [CheckName::EVEN, []],
                ],
            ],
            [
                [-1, '-3', -11, -13.0],
                fn () => Value::numeric()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10)
                    ->stopOnAnyPriorViolation(),
                [
                    [CheckName::POSITIVE, []],
                ],
            ],
            [
                [7, '9'],
                fn () => Value::numeric()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckName::EVEN, []],
                ],
            ],
            [
                ['6', 8],
                fn () => Value::numeric()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
                [
                    [CheckName::ODD, []],
                ],
            ],
            [
                [6, '8'],
                fn () => Value::numeric()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::ODD, []],
                ],
            ],
            [
                [1, 3, '11', 11.0, 13],
                fn () => Value::numeric()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, '12', 12.0, 14],
                fn () => Value::numeric()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::ODD, []],
                    [CheckName::BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, '12', 12.0, 14],
                fn () => Value::numeric()
                    ->positive()
                    ->stopOnAnyPriorViolation()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::ODD, []],
                    [CheckName::BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [6, '8', 8.0, 10],
                fn () => Value::numeric()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::ODD, []],
                ],
            ],
            [
                [1.0, 2, '3.0', '-1.0', -2.0, -3.0],
                fn () => Value::numeric()
                    ->fractional(),
                [
                    [CheckName::FRACTIONAL, []],
                ],
            ],
            [
                [1.000000001, '2.1', 3.1, '-1.001', -2.1, -3.3],
                fn () => Value::numeric()
                    ->nonFractional(),
                [
                    [CheckName::NON_FRACTIONAL, []],
                ],
            ],
            [
                [INF, -INF],
                fn () => Value::numeric()
                    ->finite(),
                [
                    [CheckName::FINITE, []],
                ],
            ],
            [
                [1.0, '2.0', 3, -1.0, '-2.0', -3.0, '999999999.0', -999999999.0],
                fn () => Value::numeric()
                    ->infinite(),
                [
                    [CheckName::INFINITE, []],
                ],
            ],
        ];
    }
}
