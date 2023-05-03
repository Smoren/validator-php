<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;

class AnyOfRuleTest extends Unit
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
        $this->assertTrue(true);
    }

    public function dataProviderForSuccess(): array
    {
        return [
            [
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::anyOf([]),
            ],
            [
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::anyOf([
                    Value::integer(),
                    Value::float(),
                ]),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::anyOf([
                    Value::integer(),
                    Value::float(),
                ])->nullable(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 2.0, 3.0],
                fn () => Value::anyOf([
                    Value::integer(),
                    Value::float()->nonFractional(),
                ])->nullable(),
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
        $this->assertTrue(true);
    }

    public function dataProviderForFail(): array
    {
        return [
            [
                [null],
                fn () => Value::anyOf([]),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                ['1', '2.2', 'a', true, false, [], (object)[1, 2, 3]],
                fn () => Value::anyOf([
                    Value::integer(),
                    Value::float(),
                ]),
                [
                    [CheckName::INTEGER, []],
                    [CheckName::FLOAT, []],
                ],
            ],
            [
                [null],
                fn () => Value::anyOf([
                    Value::integer(),
                    Value::float(),
                ]),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                [1.1, 2.1, 3.1],
                fn () => Value::anyOf([
                    Value::integer()->positive(),
                    Value::float()->positive()->nonFractional(),
                ])->nullable(),
                [
                    [CheckName::INTEGER, []],
                    [CheckName::NON_FRACTIONAL, []],
                ],
            ],
            [
                [-1.1, -2.1, -3.1],
                fn () => Value::anyOf([
                    Value::integer()->positive(),
                    Value::float()->positive()->nonFractional(),
                ])->nullable(),
                [
                    [CheckName::INTEGER, []],
                    [CheckName::POSITIVE, []],
                    [CheckName::NON_FRACTIONAL, []],
                ],
            ],
        ];
    }
}
