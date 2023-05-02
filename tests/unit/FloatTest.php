<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\Param;

class FloatTest extends Unit
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
                [1.0, 2.1, 3.1, -1.0, -2.1, -3.3],
                fn () => Value::float(),
            ],
            [
                [null, 1.0, 2.1, 3.1, -1.0, -2.1, -3.3],
                fn () => Value::float()
                    ->nullable(),
            ],
            [
                [0.0, -0.0],
                fn () => Value::float()
                    ->falsy(),
            ],
            [
                [0.1, 1.0, 1.1, -1.0, -1.1],
                fn () => Value::float()
                    ->truthy(),
            ],
            [
                [1.0, 2.1, 3.2, 10.0, 150.111],
                fn () => Value::float()
                    ->positive(),
            ],
            [
                [-1.1, -2.1, -3.0, -10.0, -150.111],
                fn () => Value::float()
                    ->negative(),
            ],
            [
                [-1.0, -2.1, -3.0, -0.0, 0.0, -150.0],
                fn () => Value::float()
                    ->nonPositive(),
            ],
            [
                [0.0, 1.0, 2.1, 3.1, 10.1, 150.222],
                fn () => Value::float()
                    ->nonNegative(),
            ],
            [
                [5.001, 6.0, 7.1, 8.2, 10.3, 150.777],
                fn () => Value::float()
                    ->greaterTran(5),
            ],
            [
                [5.0, 6.0, 7.2, 8.1, 10.0, 150.333],
                fn () => Value::float()
                    ->greaterOrEqual(5),
            ],
            [
                [4.99, 3.99, 2.99, 1.99, 0.99, -100.99],
                fn () => Value::float()
                    ->lessTran(5),
            ],
            [
                [5.0, 4.99, 3.22, 2.1, 1.0, 0.0, -100.9],
                fn () => Value::float()
                    ->lessOrEqual(5),
            ],
            [
                [1.000000001, 2.1, 3.1, -1.001, -2.1, -3.3],
                fn () => Value::float()
                    ->fractional(),
            ],
            [
                [1.0, 2.0, 3.0, -1.0, -2.0, -3.0],
                fn () => Value::float()
                    ->nonFractional(),
            ],
            [
                [1.0, 2.0, 3.0, -1.0, -2.0, -3.0, 999999999.0, -999999999.0],
                fn () => Value::float()
                    ->finite(),
            ],
            [
                [INF, -INF],
                fn () => Value::float()
                    ->infinite(),
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
                fn () => Value::float(),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::float(),
                [
                    [CheckErrorName::NOT_FLOAT, []],
                ],
            ],
            [
                [0.1, 1.0, 1.1, -1.0, -1.1],
                fn () => Value::float()
                    ->falsy(),
                [
                    [CheckErrorName::NOT_FALSY, []],
                ],
            ],
            [
                [0.0, -0.0],
                fn () => Value::float()
                    ->truthy(),
                [
                    [CheckErrorName::NOT_TRUTHY, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::float()
                    ->nullable(),
                [
                    [CheckErrorName::NOT_FLOAT, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::float()
                    ->nullable()
                    ->positive(),
                [
                    [CheckErrorName::NOT_FLOAT, []],
                ],
            ],
            [
                [0.0, -1.0, -2.0, -3.1, -10.1, -150.1],
                fn () => Value::float()
                    ->positive(),
                [
                    [CheckErrorName::NOT_POSITIVE, []],
                ],
            ],
            [
                [0.0, 1.0, 3.0, 10.1, 150.1],
                fn () => Value::float()
                    ->negative(),
                [
                    [CheckErrorName::NOT_NEGATIVE, []],
                ],
            ],
            [
                [1.0, 2.0, 3.0, 10.1, 150.1],
                fn () => Value::float()
                    ->nonPositive(),
                [
                    [CheckErrorName::NOT_NON_POSITIVE, []],
                ],
            ],
            [
                [-1.0, -2.0, -3.0, -10.1, -150.1],
                fn () => Value::float()
                    ->nonNegative(),
                [
                    [CheckErrorName::NOT_NON_NEGATIVE, []],
                ],
            ],
            [
                [5.0, 4.0, 3.5, 2.5, 1.5, 0.0, -100.0],
                fn () => Value::float()
                    ->greaterTran(5),
                [
                    [CheckErrorName::NOT_GREATER, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [4.99, 3.99, 2.99, 1.0, 0.0, -100.0],
                fn () => Value::float()
                    ->greaterOrEqual(5),
                [
                    [CheckErrorName::NOT_GREATER_OR_EQUEAL, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [5.0, 6.0, 7.0, 8.1, 10.1, 150.1],
                fn () => Value::float()
                    ->lessTran(5),
                [
                    [CheckErrorName::NOT_LESS, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [6.0, 7.0, 8.0, 10.1, 150.1],
                fn () => Value::float()
                    ->lessOrEqual(5),
                [
                    [CheckErrorName::NOT_LESS_OR_EQUEAL, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [1.0, 2.0, 3.0, -1.0, -2.0, -3.0],
                fn () => Value::float()
                    ->fractional(),
                [
                    [CheckErrorName::NOT_FRACTIONAL, []],
                ],
            ],
            [
                [1.000000001, 2.1, 3.1, -1.001, -2.1, -3.3],
                fn () => Value::float()
                    ->nonFractional(),
                [
                    [CheckErrorName::FRACTIONAL, []],
                ],
            ],
            [
                [INF, -INF],
                fn () => Value::float()
                    ->finite(),
                [
                    [CheckErrorName::NOT_FINITE, []],
                ],
            ],
            [
                [1.0, 2.0, 3.0, -1.0, -2.0, -3.0, 999999999.0, -999999999.0],
                fn () => Value::float()
                    ->infinite(),
                [
                    [CheckErrorName::NOT_INFINITE, []],
                ],
            ],
        ];
    }
}
