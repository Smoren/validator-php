<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class AllOfRuleTest extends Unit
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
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::allOf([]),
            ],
            [
                [1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::allOf([
                    Value::numeric()->greaterThan(-100),
                    Value::numeric()->lessThan(100),
                ]),
            ],
            [
                [null, 1, 2, 3, -1, -2, -3, 1.0, 1.1, 2.71, 3.14],
                fn () => Value::allOf([
                    Value::numeric()->greaterThan(-100),
                    Value::numeric()->lessThan(100),
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
        $this->expectNotToPerformAssertions();
    }

    public function dataProviderForFail(): array
    {
        return [
            [
                [null],
                fn () => Value::allOf([]),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                [-100, -100.0, -100.1, -150],
                fn () => Value::allOf([
                    Value::numeric()->greaterThan(-100),
                    Value::numeric()->lessThan(100),
                ]),
                [
                    [CheckName::GREATER, [Param::EXPECTED => -100]]
                ]
            ],
            [
                [100, 100.0, 100.1, 150],
                fn () => Value::allOf([
                    Value::numeric()->greaterThan(-100),
                    Value::numeric()->lessThan(100),
                ]),
                [
                    [CheckName::LESS, [Param::EXPECTED => 100]]
                ]
            ],
            [
                [100, 100.0, 100.1, 150],
                fn () => Value::allOf([
                    Value::numeric()->greaterThan(-100),
                    Value::numeric()->lessThan(100),
                ])->nullable(),
                [
                    [CheckName::LESS, [Param::EXPECTED => 100]]
                ]
            ],
        ];
    }
}
