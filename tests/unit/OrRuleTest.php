<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\StopValidationException;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Rules\FloatRule;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\NullableRule;
use Smoren\Validator\Rules\NumberRule;
use Smoren\Validator\Rules\OrRule;

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
                new OrRule([]),
            ],
            [
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                (new OrRule([
                    new IntegerRule(),
                    new FloatRule(),
                ])),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                (new OrRule([
                    new IntegerRule(),
                    new FloatRule(),
                ]))->nullable(),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 2.0, 3.0],
                (new OrRule([
                    new IntegerRule(),
                    (new FloatRule())->nonFractional(),
                ]))->nullable(),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFail
     * @param array $input
     * @param OrRule $rule
     * @param array $errors
     * @return void
     * @throws StopValidationException
     */
    public function testFail(array $input, OrRule $rule, array $errors): void
    {
        foreach ($input as $value) {
            try {
                $rule->validate($value);
                $this->fail();
            } catch (ValidationError $e) {
                $this->assertEquals($value, $e->getValue());
                $this->assertEquals($errors, $e->getSummary());
            }
        }
        $this->assertTrue(true);
    }

    public function dataProviderForFail(): array
    {
        return [
            [
                [null],
                new OrRule([]),
                [
                    [NullableRule::ERROR_NULL, []],
                ],
            ],
            [
                ['1', '2.2', 'a', true, false, [], (object)[1, 2, 3]],
                (new OrRule([
                    new IntegerRule(),
                    new FloatRule(),
                ])),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                    [FloatRule::ERROR_NOT_FLOAT, []],
                ],
            ],
            [
                [null],
                (new OrRule([
                    new IntegerRule(),
                    new FloatRule(),
                ])),
                [
                    [NullableRule::ERROR_NULL, []],
                ],
            ],
            [
                [1.1, 2.1, 3.1],
                (new OrRule([
                    (new IntegerRule())->positive(),
                    (new FloatRule())->positive()->nonFractional(),
                ]))->nullable(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                    [FloatRule::ERROR_FRACTIONAL, []],
                ],
            ],
            [
                [-1.1, -2.1, -3.1],
                (new OrRule([
                    (new IntegerRule())->positive(),
                    (new FloatRule())->positive()->nonFractional(),
                ]))->nullable(),
                [
                    [IntegerRule::ERROR_NOT_INTEGER, []],
                    [NumberRule::ERROR_NOT_POSITIVE, []],
                    [FloatRule::ERROR_FRACTIONAL, []],
                ],
            ],
        ];
    }
}
