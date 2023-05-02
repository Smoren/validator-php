<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class MixedTest extends Unit
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
                [true, false, 1, 1.0, 'asd', [], (object)[]],
                fn () => Value::mixed(),
            ],
            [
                [null, true, false, 1, 1.0, 'asd', [], (object)[]],
                fn () => Value::mixed()
                    ->nullable(),
            ],
            [
                [true, 1, 1.0, 235, -100.3, '1', '1.2', '-123', '-12.5', '0.0', [1, 2, 3], (object)[]],
                fn () => Value::mixed()
                    ->truthy(),
            ],
            [
                [null, true, 1, 1.0, 235, -100.3, '1', '1.2', '-123', '-12.5', '0.0', [1, 2, 3], (object)[]],
                fn () => Value::mixed()
                    ->nullable()
                    ->truthy(),
            ],
            [
                [false, 0, -0, 0.0, -0.0, '', '0', []],
                fn () => Value::mixed()
                    ->falsy(),
            ],
            [
                [null, false, 0, -0, 0.0, -0.0, '', '0', []],
                fn () => Value::mixed()
                    ->nullable()
                    ->falsy(),
            ],
            [
                [true, 1, 1.0, '1'],
                fn () => Value::mixed()
                    ->equal(1),
            ],
            [
                [false, 0, 0.0, '0'],
                fn () => Value::mixed()
                    ->equal(0),
            ],
            [
                [true],
                fn () => Value::mixed()
                    ->same(true),
            ],
            [
                [12],
                fn () => Value::mixed()
                    ->same(12),
            ],
            [
                [0.0],
                fn () => Value::mixed()
                    ->same(0.0),
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
                fn () => Value::mixed(),
                [
                    [CheckName::NOT_NULL, []],
                ]
            ],
            [
                [false, 0, -0, 0.0, -0.0, '', '0', []],
                fn () => Value::mixed()
                    ->truthy(),
                [
                    [CheckName::TRUTHY, []],
                ]
            ],
            [
                [true, 1, 1.0, 235, -100.3, '1', '1.2', '-123', '-12.5', '0.0', [1, 2, 3], (object)[]],
                fn () => Value::mixed()
                    ->falsy(),
                [
                    [CheckName::FALSY, []],
                ]
            ],
            [
                [false, 0, 0.0, '0'],
                fn () => Value::mixed()
                    ->equal(1),
                [
                    [CheckName::EQUAL, [Param::EXPECTED => 1]],
                ]
            ],
            [
                [true, 1, 1.0, '1'],
                fn () => Value::mixed()
                    ->equal(0),
                [
                    [CheckName::EQUAL, [Param::EXPECTED => 0]],
                ]
            ],
            [
                [1],
                fn () => Value::mixed()
                    ->same(true),
                [
                    [CheckName::SAME, [Param::EXPECTED => true]],
                ]
            ],
            [
                [12],
                fn () => Value::mixed()
                    ->same(12.0),
                [
                    [CheckName::SAME, [Param::EXPECTED => 12.0]],
                ]
            ],
            [
                [0.0],
                fn () => Value::mixed()
                    ->same(0),
                [
                    [CheckName::SAME, [Param::EXPECTED => 0]],
                ]
            ],
        ];
    }
}
