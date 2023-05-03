<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class IntegerTest extends Unit
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
                    ->greaterThan(5),
            ],
            [
                [5, 6, 7, 8, 10, 150],
                fn () => Value::integer()
                    ->greaterOrEqual(5),
            ],
            [
                [4, 3, 2, 1, 0, -100],
                fn () => Value::integer()
                    ->lessThan(5),
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
                fn () => Value::integer(),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                fn () => Value::integer(),
                [
                    [CheckName::INTEGER, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                fn () => Value::integer()
                    ->nullable(),
                [
                    [CheckName::INTEGER, []],
                ],
            ],
            [
                [1, -1, 2, -2, 3],
                fn () => Value::integer()
                    ->falsy(),
                [
                    [CheckName::FALSY, []],
                ],
            ],
            [
                [0, -0],
                fn () => Value::integer()
                    ->truthy(),
                [
                    [CheckName::TRUTHY, []],
                ],
            ],
            [
                ['1', 'a', true, false, []],
                fn () => Value::integer()
                    ->nullable()
                    ->positive(),
                [
                    [CheckName::INTEGER, []],
                ],
            ],
            [
                [0, -1, -2, -3, -10, -150],
                fn () => Value::integer()
                    ->positive(),
                [
                    [CheckName::POSITIVE, []],
                ],
            ],
            [
                [0, 1, 3, 10, 150],
                fn () => Value::integer()
                    ->negative(),
                [
                    [CheckName::NEGATIVE, []],
                ],
            ],
            [
                [1, 2, 3, 10, 150],
                fn () => Value::integer()
                    ->nonPositive(),
                [
                    [CheckName::NON_POSITIVE, []],
                ],
            ],
            [
                [-1, -2, -3, -10, -150],
                fn () => Value::integer()
                    ->nonNegative(),
                [
                    [CheckName::NON_NEGATIVE, []],
                ],
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                fn () => Value::integer()
                    ->greaterThan(5),
                [
                    [CheckName::GREATER, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [4, 3, 2, 1, 0, -100],
                fn () => Value::integer()
                    ->greaterOrEqual(5),
                [
                    [CheckName::GREATER_OR_EQUEAL, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [5, 6, 7, 8, 10, 150],
                fn () => Value::integer()
                    ->lessThan(5),
                [
                    [CheckName::LESS, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [6, 7, 8, 10, 150],
                fn () => Value::integer()
                    ->lessOrEqual(5),
                [
                    [CheckName::LESS_OR_EQUEAL, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [1, 3, 11, 13],
                fn () => Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckName::EVEN, []],
                    [CheckName::IN_INTERVAL, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [-1, -3, -11, -13],
                fn () => Value::integer()
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
                [-1, -3, -11, -13],
                fn () => Value::integer()
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
                [-1, -3, -11, -13],
                fn () => Value::integer()
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
                [1, 3, 5, 7, 9],
                fn () => Value::integer()
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
                [-1, -3, -11, -13],
                fn () => Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10)
                    ->stopOnAnyPriorViolation(),
                [
                    [CheckName::POSITIVE, []],
                ],
            ],
            [
                [7, 9],
                fn () => Value::integer()
                    ->positive()
                    ->even()
                    ->inInterval(5, 10),
                [
                    [CheckName::EVEN, []],
                ],
            ],
            [
                [6, 8],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->inInterval(5, 10),
                [
                    [CheckName::ODD, []],
                ],
            ],
            [
                [6, 8],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::ODD, []],
                ],
            ],
            [
                [1, 3, 11, 13],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [2, 4, 12, 14],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::ODD, []],
                    [CheckName::BETWEEN, ['start' => 5, 'end' => 10]],
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
                    [CheckName::ODD, []],
                    [CheckName::BETWEEN, ['start' => 5, 'end' => 10]],
                ],
            ],
            [
                [6, 8, 10],
                fn () => Value::integer()
                    ->positive()
                    ->odd()
                    ->between(5, 10),
                [
                    [CheckName::ODD, []],
                ],
            ],
        ];
    }
}
