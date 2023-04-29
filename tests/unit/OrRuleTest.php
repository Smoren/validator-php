<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Rules\OrRule;
use Smoren\Validator\Structs\CheckErrorName;

class OrRuleTest extends Unit
{
    /**
     * @dataProvider dataProviderForSuccess
     * @param array $input
     * @param OrRule $rule
     * @return void
     */
    public function testSuccess(array $input, OrRule $rule): void
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
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                Value::or([]),
            ],
            [
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                Value::or([
                    Value::integer(),
                    Value::float(),
                ]),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                Value::or([
                    Value::integer(),
                    Value::float(),
                ])->nullable(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 2.0, 3.0],
                Value::or([
                    Value::integer(),
                    Value::float()->nonFractional(),
                ])->nullable(),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFail
     * @param array $input
     * @param OrRule $rule
     * @param array $errors
     * @return void
     */
    public function testFail(array $input, OrRule $rule, array $errors): void
    {
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
                Value::or([]),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                ['1', '2.2', 'a', true, false, [], (object)[1, 2, 3]],
                Value::or([
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
                Value::or([
                    Value::integer(),
                    Value::float(),
                ]),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                [1.1, 2.1, 3.1],
                Value::or([
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
                Value::or([
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
