<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class BooleanTest extends Unit
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
                [true, false],
                fn () => Value::boolean(),
            ],
            [
                [null, true, false],
                fn () => Value::boolean()
                    ->nullable(),
            ],
            [
                [true],
                fn () => Value::boolean()
                    ->truthy(),
            ],
            [
                [null, true],
                fn () => Value::boolean()
                    ->nullable()
                    ->truthy(),
            ],
            [
                [false],
                fn () => Value::boolean()
                    ->falsy(),
            ],
            [
                [null, false],
                fn () => Value::boolean()
                    ->nullable()
                    ->falsy(),
            ],
            [
                [true],
                fn () => Value::boolean()
                    ->equal(true),
            ],
            [
                [false],
                fn () => Value::boolean()
                    ->equal(false),
            ],
            [
                [true],
                fn () => Value::boolean()
                    ->equal(1),
            ],
            [
                [false],
                fn () => Value::boolean()
                    ->equal(0),
            ],
            [
                [true],
                fn () => Value::boolean()
                    ->same(true),
            ],
            [
                [false],
                fn () => Value::boolean()
                    ->same(false),
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
                fn () => Value::boolean(),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                ['1', 'a', 1, 1.2, []],
                fn () => Value::boolean(),
                [
                    [CheckName::BOOL, []],
                ],
            ],
            [
                [false],
                fn () => Value::boolean()
                    ->truthy(),
                [
                    [CheckName::TRUTHY, []],
                ],
            ],
            [
                [false],
                fn () => Value::boolean()
                    ->nullable()
                    ->truthy(),
                [
                    [CheckName::TRUTHY, []],
                ],
            ],
            [
                [true],
                fn () => Value::boolean()
                    ->falsy(),
                [
                    [CheckName::FALSY, []],
                ],
            ],
            [
                [true],
                fn () => Value::boolean()
                    ->nullable()
                    ->falsy(),
                [
                    [CheckName::FALSY, []],
                ],
            ],
            [
                [true],
                fn () => Value::boolean()
                    ->equal(0),
                [
                    [CheckName::EQUAL, [Param::EXPECTED => 0]],
                ],
            ],
            [
                [false],
                fn () => Value::boolean()
                    ->equal(1),
                [
                    [CheckName::EQUAL, [Param::EXPECTED => 1]],
                ],
            ],
            [
                [true],
                fn () => Value::boolean()
                    ->same(1),
                [
                    [CheckName::SAME, [Param::EXPECTED => 1]],
                ],
            ],
            [
                [false],
                fn () => Value::boolean()
                    ->same(0),
                [
                    [CheckName::SAME, [Param::EXPECTED => 0]],
                ],
            ],
        ];
    }
}
