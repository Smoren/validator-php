<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class BoolTest extends Unit
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
                [true, false],
                fn () => Value::bool(),
            ],
            [
                [null, true, false],
                fn () => Value::bool()
                    ->nullable(),
            ],
            [
                [true],
                fn () => Value::bool()
                    ->truthy(),
            ],
            [
                [null, true],
                fn () => Value::bool()
                    ->nullable()
                    ->truthy(),
            ],
            [
                [false],
                fn () => Value::bool()
                    ->falsy(),
            ],
            [
                [null, false],
                fn () => Value::bool()
                    ->nullable()
                    ->falsy(),
            ],
            [
                [true],
                fn () => Value::bool()
                    ->equal(true),
            ],
            [
                [false],
                fn () => Value::bool()
                    ->equal(false),
            ],
            [
                [true],
                fn () => Value::bool()
                    ->equal(1),
            ],
            [
                [false],
                fn () => Value::bool()
                    ->equal(0),
            ],
            [
                [true],
                fn () => Value::bool()
                    ->same(true),
            ],
            [
                [false],
                fn () => Value::bool()
                    ->same(false),
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
                fn () => Value::bool(),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                ['1', 'a', 1, 1.2, []],
                fn () => Value::bool(),
                [
                    [CheckName::BOOL, []],
                ],
            ],
            [
                [false],
                fn () => Value::bool()
                    ->truthy(),
                [
                    [CheckName::TRUTHY, []],
                ],
            ],
            [
                [false],
                fn () => Value::bool()
                    ->nullable()
                    ->truthy(),
                [
                    [CheckName::TRUTHY, []],
                ],
            ],
            [
                [true],
                fn () => Value::bool()
                    ->falsy(),
                [
                    [CheckName::FALSY, []],
                ],
            ],
            [
                [true],
                fn () => Value::bool()
                    ->nullable()
                    ->falsy(),
                [
                    [CheckName::FALSY, []],
                ],
            ],
            [
                [true],
                fn () => Value::bool()
                    ->equal(0),
                [
                    [CheckName::EQUAL, [Param::EXPECTED => 0]],
                ],
            ],
            [
                [false],
                fn () => Value::bool()
                    ->equal(1),
                [
                    [CheckName::EQUAL, [Param::EXPECTED => 1]],
                ],
            ],
            [
                [true],
                fn () => Value::bool()
                    ->same(1),
                [
                    [CheckName::SAME, [Param::EXPECTED => 1]],
                ],
            ],
            [
                [false],
                fn () => Value::bool()
                    ->same(0),
                [
                    [CheckName::SAME, [Param::EXPECTED => 0]],
                ],
            ],
        ];
    }
}
