<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
use Smoren\Validator\Factories\Validate;
use Smoren\Validator\Interfaces\ContainerRuleInterface;
use Smoren\Validator\Rules\ContainerRule;
use Smoren\Validator\Rules\IntegerRule;
use Smoren\Validator\Rules\NumericRule;

class ContainerTest extends Unit
{
    /**
     * @dataProvider dataProviderForSuccess
     * @param array $input
     * @param ContainerRuleInterface $rule
     * @return void
     */
    public function testSuccess(array $input, ContainerRuleInterface $rule): void
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
                [[], (object)[], [1, 2, 3], (object)['a' => 1], ['a' => 1]],
                Validate::container(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                Validate::container()
                    ->array(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1], []],
                Validate::container()
                    ->indexedArray(),
            ],
            [
                [[1, 2, 'a' => 3], [1 => 2], ['test' => true, 'a' => []]],
                Validate::container()
                    ->associativeArray(),
            ],
            [
                [(object)[1, 2, 3], (object)[1, 2, 3, 4, 5], (object)[1]],
                Validate::container()
                    ->object(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                Validate::container()
                    ->lengthIs(Validate::integer()->odd()),
            ],
            [
                [['a' => 1, 'b' => 2], ['a' => [], 'd', 'b' => null]],
                Validate::container()
                    ->hasAttribute('a')
                    ->hasAttribute('b'),
            ],
            [
                [['a' => 1, 'b' => 2], ['a' => '1.23', 'd', 'b' => null]],
                Validate::container()
                    ->hasAttribute('a', Validate::numeric()),
            ],
            [
                [[2, 4, 6, 8], [4], [1000, 2000, 8000], []],
                Validate::container()
                    ->everyValueIs(Validate::integer()->even()),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFail
     * @param array $input
     * @param ContainerRuleInterface $rule
     * @param array $errors
     * @return void
     */
    public function testFail(array $input, ContainerRuleInterface $rule, array $errors): void
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
                [1, '2', true, false, 'asd'],
                Validate::container(),
                [
                    [ContainerRule::ERROR_NOT_CONTAINER, []],
                ],
            ],
            [
                [[1, 2], [1, 2, 3, 4], []],
                Validate::container()
                    ->lengthIs(Validate::integer()->odd()),
                [
                    [ContainerRule::ERROR_BAD_LENGTH, ['violations' => [['not_odd', []]]]],
                ],
            ],
            [
                [(object)[1, 2, 3], (object)[1, 2, 3, 4, 5], (object)[1]],
                Validate::container()
                    ->array(),
                [
                    [ContainerRule::ERROR_NOT_ARRAY, []],
                ],
            ],
            [
                [[1, 2, 'a' => 3], [1 => 2], (object)[]],
                Validate::container()
                    ->indexedArray(),
                [
                    [ContainerRule::ERROR_NOT_INDEXED_ARRAY, []],
                ],
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                Validate::container()
                    ->object(),
                [
                    [ContainerRule::ERROR_NOT_OBJECT, []],
                ],
            ],
            [
                [['a' => 1], ['a' => [], 'd']],
                Validate::container()
                    ->hasAttribute('a')
                    ->hasAttribute('b'),
                [
                    [ContainerRule::ERROR_ATTRIBUTE_NOT_EXIST, ['name' => 'b']],
                ],
            ],
            [
                [['a' => '1a', 'b' => 2], ['a' => false, 'd', 'b' => null]],
                Validate::container()
                    ->hasAttribute('a', Validate::numeric()),
                [
                    [ContainerRule::ERROR_BAD_ATTRIBUTE, ['name' => 'a', 'violations' => [['not_numeric', []]]]],
                ],
            ],
            [
                [['b' => 2], ['d', 'b' => null]],
                Validate::container()
                    ->hasAttribute('a', Validate::numeric()),
                [
                    [ContainerRule::ERROR_ATTRIBUTE_NOT_EXIST, ['name' => 'a']],
                ],
            ],
            [
                [[2, 4, 7, 8], [1], [1001, 2000, 8000]],
                Validate::container()
                    ->everyValueIs(Validate::integer()->even()),
                [
                    [ContainerRule::ERROR_SOME_VALUES_BAD, ['violations' => [['not_even', []]]]],
                ],
            ],
        ];
    }
}
