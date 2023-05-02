<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class AndRuleTest extends Unit
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
                fn () => Value::and([]),
            ],
            [
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::and([
                    Value::numeric()->greaterTran(-100),
                    Value::numeric()->lessTran(100),
                ]),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::and([
                    Value::numeric()->greaterTran(-100),
                    Value::numeric()->lessTran(100),
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
                fn () => Value::and([]),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                [-100, -100.0, -100.1, -150],
                fn () => Value::and([
                    Value::numeric()->greaterTran(-100),
                    Value::numeric()->lessTran(100),
                ]),
                [
                    [CheckName::GREATER, [Param::EXPECTED => -100]]
                ]
            ],
            [
                [100, 100.0, 100.1, 150],
                fn () => Value::and([
                    Value::numeric()->greaterTran(-100),
                    Value::numeric()->lessTran(100),
                ]),
                [
                    [CheckName::LESS, [Param::EXPECTED => 100]]
                ]
            ],
            [
                [100, 100.0, 100.1, 150],
                fn () => Value::and([
                    Value::numeric()->greaterTran(-100),
                    Value::numeric()->lessTran(100),
                ])->nullable(),
                [
                    [CheckName::LESS, [Param::EXPECTED => 100]]
                ]
            ],
        ];
    }
}
