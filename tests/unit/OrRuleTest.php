<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckErrorName;

class OrRuleTest extends Unit
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
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::or([]),
            ],
            [
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::or([
                    Value::integer(),
                    Value::float(),
                ]),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::or([
                    Value::integer(),
                    Value::float(),
                ])->nullable(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 2.0, 3.0],
                fn () => Value::or([
                    Value::integer(),
                    Value::float()->nonFractional(),
                ])->nullable(),
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
                fn () => Value::or([]),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                ['1', '2.2', 'a', true, false, [], (object)[1, 2, 3]],
                fn () => Value::or([
                    Value::integer(),
                    Value::float(),
                ]),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                    [CheckErrorName::NOT_FLOAT, []],
                ],
            ],
            [
                [null],
                fn () => Value::or([
                    Value::integer(),
                    Value::float(),
                ]),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                [1.1, 2.1, 3.1],
                fn () => Value::or([
                    Value::integer()->positive(),
                    Value::float()->positive()->nonFractional(),
                ])->nullable(),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                    [CheckErrorName::FRACTIONAL, []],
                ],
            ],
            [
                [-1.1, -2.1, -3.1],
                fn () => Value::or([
                    Value::integer()->positive(),
                    Value::float()->positive()->nonFractional(),
                ])->nullable(),
                [
                    [CheckErrorName::NOT_INTEGER, []],
                    [CheckErrorName::NOT_POSITIVE, []],
                    [CheckErrorName::FRACTIONAL, []],
                ],
            ],
        ];
    }
}
