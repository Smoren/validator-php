<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class NumericTest extends Unit
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
                    ->greaterTran(5),
            ],
            [
                [5, 6, 7, '8', 10, '150'],
                fn () => Value::numeric()
                    ->greaterOrEqual(5),
            ],
            [
                [4, 3, 2, '1', 0, '-100'],
                fn () => Value::numeric()
                    ->lessTran(5),
            ],
            [
                [5, 4, 3, 2, '1', 0, '-100'],
                fn () => Value::numeric()
                    ->lessOrEqual(5),
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
                $this->assertSame($errors, $e->getViolatedRestrictions());
            }
        }
        $this->assertTrue(true);
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
                    ->greaterTran(5),
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
                    ->lessTran(5),
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
        ];
    }
}
