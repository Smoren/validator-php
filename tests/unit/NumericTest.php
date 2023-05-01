<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckErrorName;

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
                fn () => Value::numeric(),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::numeric(),
                [
                    [CheckErrorName::NOT_NUMERIC, []],
                ],
            ],
            [
                [1, 1.1, -1, -1.1, '2', '2.2', '-2', '-2.2', '3'],
                fn () => Value::numeric()
                    ->falsy(),
                [
                    [CheckErrorName::NOT_FALSY, []],
                ],
            ],
            [
                [0, -0, 0.0, -0.0, '0', '-0', '0.0', '-0.0'],
                fn () => Value::numeric()
                    ->truthy(),
                [
                    [CheckErrorName::NOT_TRUTHY, []],
                ],
            ],
            [
                ['1', '2.1', '3', '-1', '-2', '-3'],
                fn () => Value::numeric()
                    ->number(),
                [
                    [CheckErrorName::NOT_NUMBER, []],
                ],
            ],
            [
                [1, 2.1, 3, -1, -2, -3],
                fn () => Value::numeric()
                    ->string(),
                [
                    [CheckErrorName::NOT_STRING, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::numeric()
                    ->nullable(),
                [
                    [CheckErrorName::NOT_NUMERIC, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::numeric()
                    ->nullable()
                    ->positive(),
                [
                    [CheckErrorName::NOT_NUMERIC, []],
                ],
            ],
            [
                [0, -1, -2, -3, -10, -150],
                fn () => Value::numeric()
                    ->positive(),
                [
                    [CheckErrorName::NOT_POSITIVE, []],
                ],
            ],
            [
                [0, 1, 3, 10, 150],
                fn () => Value::numeric()
                    ->negative(),
                [
                    [CheckErrorName::NOT_NEGATIVE, []],
                ],
            ],
            [
                [1, 2, 3, 10, 150],
                fn () => Value::numeric()
                    ->nonPositive(),
                [
                    [CheckErrorName::NOT_NON_POSITIVE, []],
                ],
            ],
            [
                [-1, -2, -3, -10, -150],
                fn () => Value::numeric()
                    ->nonNegative(),
                [
                    [CheckErrorName::NOT_NON_NEGATIVE, []],
                ],
            ],
            [
                [5, 4, 3, 2, 1, 0, -100],
                fn () => Value::numeric()
                    ->greaterTran(5),
                [
                    [CheckErrorName::NOT_GREATER, ['number' => 5]],
                ],
            ],
            [
                [4, 3, 2, 1, 0, -100],
                fn () => Value::numeric()
                    ->greaterOrEqual(5),
                [
                    [CheckErrorName::NOT_GREATER_OR_EQUEAL, ['number' => 5]],
                ],
            ],
            [
                [5, 6, 7, 8, 10, 150],
                fn () => Value::numeric()
                    ->lessTran(5),
                [
                    [CheckErrorName::NOT_LESS, ['number' => 5]],
                ],
            ],
            [
                [6, 7, 8, 10, 150],
                fn () => Value::numeric()
                    ->lessOrEqual(5),
                [
                    [CheckErrorName::NOT_LESS_OR_EQUEAL, ['number' => 5]],
                ],
            ],
        ];
    }
}
