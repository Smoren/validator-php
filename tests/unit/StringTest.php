<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;
use Smoren\Validator\Structs\RuleName;

class StringTest extends Unit
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
                ['86489164-8624-44d4-bdb4-432036c354a3', '576c7171-ba19-4b9c-a30c-97a4205d0825', '576C7171-BA19-4B9C-A30C-97A4205D0825'],
                fn () => Value::string()
                    ->uuid(),
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
                    ->lengthIs(Value::integer()->lessThan(7)),
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
                fn () => Value::string(),
                [
                    [CheckName::NOT_NULL, []],
                ],
            ],
            [
                [1, 1.1, 0, 0.0, [], (object)[]],
                fn () => Value::string(),
                [
                    [CheckName::STRING, []],
                ],
            ],
            [
                ['', 'a', 'abc', '--10', 'a1', '1a'],
                fn () => Value::string()
                    ->numeric(),
                [
                    [CheckName::NUMERIC, []],
                ],
            ],
            [
                ['1', 'a', '123', 'abc', '123abc'],
                fn () => Value::string()
                    ->empty(),
                [
                    [CheckName::EMPTY, []],
                ],
            ],
            [
                [''],
                fn () => Value::string()
                    ->notEmpty(),
                [
                    [CheckName::NOT_EMPTY, []],
                ],
            ],
            [
                ['my@email', 'mail.com', '@gmail.com'],
                fn () => Value::string()
                    ->match('/^[a-z0-9_]+@[a-z0-9_]+\.[a-z]+$/'),
                [
                    [CheckName::MATCH, ['regex' => '/^[a-z0-9_]+@[a-z0-9_]+\.[a-z]+$/']],
                ],
            ],
            [
                ['186489164-8624-44d4-bdb4-432036c354a3', '', '123'],
                fn () => Value::string()
                    ->uuid(),
                [
                    [CheckName::UUID, []],
                ],
            ],
            [
                ['defg', 'bcd', 'abcd', 'cdfg', ''],
                fn () => Value::string()
                    ->hasSubstring('cde'),
                [
                    [CheckName::HAS_SUBSTRING, ['substring' => 'cde']],
                ],
            ],
            [
                ['aabc', 'acabc', '1abc', 'qqq', ''],
                fn () => Value::string()
                    ->startsWith('abc'),
                [
                    [CheckName::STARTS_WITH, ['substring' => 'abc']],
                ],
            ],
            [
                ['abcabc1', '123ab', 'ab', ''],
                fn () => Value::string()
                    ->endsWith('abc'),
                [
                    [CheckName::ENDS_WITH, ['substring' => 'abc']],
                ],
            ],
            [
                ['abcabcabc', '123123123123', 'aaaaaaaaaaaaaaaaa'],
                fn () => Value::string()
                    ->lengthIs(Value::integer()->lessThan(7)),
                [
                    [CheckName::LENGTH_IS, [
                        Param::RULE => RuleName::INTEGER,
                        Param::VIOLATED_RESTRICTIONS => [
                            [
                                CheckName::LESS,
                                [Param::EXPECTED => 7],
                            ]
                        ],
                    ]],
                ],
            ],
        ];
    }
}
