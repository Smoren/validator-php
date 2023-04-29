<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Validate;
use Smoren\Validator\Rules\FloatRule;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\NumericRule;
use Smoren\Validator\Rules\OrRule;
use Smoren\Validator\Rules\Rule;

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
                Validate::or([]),
            ],
            [
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                Validate::or([
                    Validate::integer(),
                    Validate::float(),
                ]),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                Validate::or([
                    Validate::integer(),
                    Validate::float(),
                ])->nullable(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 2.0, 3.0],
                Validate::or([
                    Validate::integer(),
                    Validate::float()->nonFractional(),
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
                Validate::or([]),
                [
                    [Rule::ERROR_NULL, []],
                ],
            ],
            [
                ['1', '2.2', 'a', true, false, [], (object)[1, 2, 3]],
                Validate::or([
                    Validate::integer(),
                    Validate::float(),
                ]),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                    [FloatRule::ERROR_NOT_FLOAT, []],
                ],
            ],
            [
                [null],
                Validate::or([
                    Validate::integer(),
                    Validate::float(),
                ]),
                [
                    [Rule::ERROR_NULL, []],
                ],
            ],
            [
                [1.1, 2.1, 3.1],
                Validate::or([
                    Validate::integer()->positive(),
                    Validate::float()->positive()->nonFractional(),
                ])->nullable(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                    [FloatRule::ERROR_FRACTIONAL, []],
                ],
            ],
            [
                [-1.1, -2.1, -3.1],
                Validate::or([
                    Validate::integer()->positive(),
                    Validate::float()->positive()->nonFractional(),
                ])->nullable(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                    [NumericRule::ERROR_NOT_POSITIVE, []],
                    [FloatRule::ERROR_FRACTIONAL, []],
                ],
            ],
        ];
    }
}
