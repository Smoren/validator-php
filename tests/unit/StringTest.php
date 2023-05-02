<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\RuleInterface;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Structs\CheckErrorName;
use Smoren\Validator\Structs\Param;
use Smoren\Validator\Structs\RuleName;

class StringTest extends Unit
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
                ['', '1', 'a', '123', 'abc'],
                fn () => Value::string(),
            ],
            [
                [null, '', '1', 'a', '123', 'abc'],
                fn () => Value::string()
                    ->nullable(),
            ],
            [
                ['1', '0', '-0', '-1', '0.0', '-0.0', '1.1', '-1.1', '123'],
                fn () => Value::string()
                    ->numeric(),
            ],
            [
                [''],
                fn () => Value::string()
                    ->empty(),
            ],
            [
                ['1', 'a', '123', 'abc', '123abc'],
                fn () => Value::string()
                    ->notEmpty(),
            ],
            [
                ['my@email.com', 'test@mail.com', 'nya@gmail.com'],
                fn () => Value::string()
                    ->match('/^[a-z0-9_]+@[a-z0-9_]+\.[a-z]+$/'),
            ],
            [
                ['abcdefg', 'cde', 'abcde', 'cdefg', 'cdecde'],
                fn () => Value::string()
                    ->hasSubstring('cde'),
            ],
            [
                ['abcdefg', 'abc', 'abcd'],
                fn () => Value::string()
                    ->startsWith('abc'),
            ],
            [
                ['abcabc', '123abc', 'abc'],
                fn () => Value::string()
                    ->endsWith('abc'),
            ],
            [
                ['abcabc', '123abc', 'abc'],
                fn () => Value::string()
                    ->lengthIs(Value::integer()->lessTran(7)),
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
                fn () => Value::string(),
                [
                    [CheckErrorName::NULL, []],
                ],
            ],
            [
                [1, 1.1, 0, 0.0, [], (object)[]],
                fn () => Value::string(),
                [
                    [CheckErrorName::NOT_STRING, []],
                ],
            ],
            [
                ['', 'a', 'abc', '--10', 'a1', '1a'],
                fn () => Value::string()
                    ->numeric(),
                [
                    [CheckErrorName::NOT_NUMERIC, []],
                ],
            ],
            [
                ['1', 'a', '123', 'abc', '123abc'],
                fn () => Value::string()
                    ->empty(),
                [
                    [CheckErrorName::NOT_EMPTY, []],
                ],
            ],
            [
                [''],
                fn () => Value::string()
                    ->notEmpty(),
                [
                    [CheckErrorName::EMPTY, []],
                ],
            ],
            [
                ['my@email', 'mail.com', '@gmail.com'],
                fn () => Value::string()
                    ->match('/^[a-z0-9_]+@[a-z0-9_]+\.[a-z]+$/'),
                [
                    [CheckErrorName::NOT_MATCH, ['regex' => '/^[a-z0-9_]+@[a-z0-9_]+\.[a-z]+$/']],
                ],
            ],
            [
                ['defg', 'bcd', 'abcd', 'cdfg', ''],
                fn () => Value::string()
                    ->hasSubstring('cde'),
                [
                    [CheckErrorName::HAS_NOT_SUBSTRING, ['substring' => 'cde']],
                ],
            ],
            [
                ['aabc', 'acabc', '1abc', 'qqq', ''],
                fn () => Value::string()
                    ->startsWith('abc'),
                [
                    [CheckErrorName::NOT_STARTS_WITH, ['substring' => 'abc']],
                ],
            ],
            [
                ['abcabc1', '123ab', 'ab', ''],
                fn () => Value::string()
                    ->endsWith('abc'),
                [
                    [CheckErrorName::NOT_ENDS_WITH, ['substring' => 'abc']],
                ],
            ],
            [
                ['abcabcabc', '123123123123', 'aaaaaaaaaaaaaaaaa'],
                fn () => Value::string()
                    ->lengthIs(Value::integer()->lessTran(7)),
                [
                    [CheckErrorName::INVALID_LENGTH, [
                        Param::RULE => RuleName::INTEGER,
                        Param::VIOLATIONS => [
                            [
                                CheckErrorName::NOT_LESS,
                                [Param::EXPECTED => 7],
                            ]
                        ],
                    ]],
                ],
            ],
        ];
    }
}
