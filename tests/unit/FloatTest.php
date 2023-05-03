<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class FloatTest extends Unit
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
                    ->greaterThan(5),
            ],
            [
                [5.0, 6.0, 7.2, 8.1, 10.0, 150.333],
                fn () => Value::float()
                    ->greaterOrEqual(5),
            ],
            [
                [4.99, 3.99, 2.99, 1.99, 0.99, -100.99],
                fn () => Value::float()
                    ->lessThan(5),
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
                fn () => Value::float(),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::float(),
                [
                    [CheckName::FLOAT, []],
                ],
            ],
            [
                [0.1, 1.0, 1.1, -1.0, -1.1],
                fn () => Value::float()
                    ->falsy(),
                [
                    [CheckName::FALSY, []],
                ],
            ],
            [
                [0.0, -0.0],
                fn () => Value::float()
                    ->truthy(),
                [
                    [CheckName::TRUTHY, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::float()
                    ->nullable(),
                [
                    [CheckName::FLOAT, []],
                ],
            ],
            [
                ['a', true, false, []],
                fn () => Value::float()
                    ->nullable()
                    ->positive(),
                [
                    [CheckName::FLOAT, []],
                ],
            ],
            [
                [0.0, -1.0, -2.0, -3.1, -10.1, -150.1],
                fn () => Value::float()
                    ->positive(),
                [
                    [CheckName::POSITIVE, []],
                ],
            ],
            [
                [0.0, 1.0, 3.0, 10.1, 150.1],
                fn () => Value::float()
                    ->negative(),
                [
                    [CheckName::NEGATIVE, []],
                ],
            ],
            [
                [1.0, 2.0, 3.0, 10.1, 150.1],
                fn () => Value::float()
                    ->nonPositive(),
                [
                    [CheckName::NON_POSITIVE, []],
                ],
            ],
            [
                [-1.0, -2.0, -3.0, -10.1, -150.1],
                fn () => Value::float()
                    ->nonNegative(),
                [
                    [CheckName::NON_NEGATIVE, []],
                ],
            ],
            [
                [5.0, 4.0, 3.5, 2.5, 1.5, 0.0, -100.0],
                fn () => Value::float()
                    ->greaterThan(5),
                [
                    [CheckName::GREATER, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [4.99, 3.99, 2.99, 1.0, 0.0, -100.0],
                fn () => Value::float()
                    ->greaterOrEqual(5),
                [
                    [CheckName::GREATER_OR_EQUEAL, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [5.0, 6.0, 7.0, 8.1, 10.1, 150.1],
                fn () => Value::float()
                    ->lessThan(5),
                [
                    [CheckName::LESS, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [6.0, 7.0, 8.0, 10.1, 150.1],
                fn () => Value::float()
                    ->lessOrEqual(5),
                [
                    [CheckName::LESS_OR_EQUEAL, [Param::EXPECTED => 5]],
                ],
            ],
            [
                [1.0, 2.0, 3.0, -1.0, -2.0, -3.0],
                fn () => Value::float()
                    ->fractional(),
                [
                    [CheckName::FRACTIONAL, []],
                ],
            ],
            [
                [1.000000001, 2.1, 3.1, -1.001, -2.1, -3.3],
                fn () => Value::float()
                    ->nonFractional(),
                [
                    [CheckName::NON_FRACTIONAL, []],
                ],
            ],
            [
                [INF, -INF],
                fn () => Value::float()
                    ->finite(),
                [
                    [CheckName::FINITE, []],
                ],
            ],
            [
                [1.0, 2.0, 3.0, -1.0, -2.0, -3.0, 999999999.0, -999999999.0],
                fn () => Value::float()
                    ->infinite(),
                [
                    [CheckName::INFINITE, []],
                ],
            ],
        ];
    }
}
