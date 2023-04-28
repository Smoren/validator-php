<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Exceptions\ValidationError;
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
                new ContainerRule(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                (new ContainerRule())
                    ->array(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1], []],
                (new ContainerRule())
                    ->indexedArray(),
            ],
            [
                [[1, 2, 'a' => 3], [1 => 2], ['test' => true, 'a' => []]],
                (new ContainerRule())
                    ->associativeArray(),
            ],
            [
                [(object)[1, 2, 3], (object)[1, 2, 3, 4, 5], (object)[1]],
                (new ContainerRule())
                    ->object(),
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                (new ContainerRule())
                    ->lengthIs((new IntegerRule())->odd()),
            ],
            [
                [['a' => 1, 'b' => 2], ['a' => [], 'd', 'b' => null]],
                (new ContainerRule())
                    ->hasAttribute('a')
                    ->hasAttribute('b'),
            ],
            [
                [['a' => 1, 'b' => 2], ['a' => '1.23', 'd', 'b' => null]],
                (new ContainerRule())
                    ->hasAttribute('a', new NumericRule()),
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
                new ContainerRule(),
                [
                    [ContainerRule::ERROR_NOT_CONTAINER, []],
                ],
            ],
            [
                [[1, 2], [1, 2, 3, 4], []],
                (new ContainerRule())
                    ->lengthIs((new IntegerRule())->odd()),
                [
                    [ContainerRule::ERROR_LENGTH_IS_NOT, ['violations' => [['not_odd', []]]]],
                ],
            ],
            [
                [(object)[1, 2, 3], (object)[1, 2, 3, 4, 5], (object)[1]],
                (new ContainerRule())
                    ->array(),
                [
                    [ContainerRule::ERROR_NOT_ARRAY, []],
                ],
            ],
            [
                [[1, 2, 'a' => 3], [1 => 2], (object)[]],
                (new ContainerRule())
                    ->indexedArray(),
                [
                    [ContainerRule::ERROR_NOT_INDEXED_ARRAY, []],
                ],
            ],
            [
                [[1, 2, 3], [1, 2, 3, 4, 5], [1]],
                (new ContainerRule())
                    ->object(),
                [
                    [ContainerRule::ERROR_NOT_OBJECT, []],
                ],
            ],
            [
                [['a' => 1], ['a' => [], 'd']],
                (new ContainerRule())
                    ->hasAttribute('a')
                    ->hasAttribute('b'),
                [
                    [ContainerRule::ERROR_ATTRIBUTE_NOT_EXIST, ['name' => 'b']],
                ],
            ],
            [
                [['a' => '1a', 'b' => 2], ['a' => false, 'd', 'b' => null]],
                (new ContainerRule())
                    ->isAttribute('a', new NumericRule()),
                [
                    [ContainerRule::ERROR_BAD_ATTRIBUTE, ['name' => 'a', 'violations' => [['not_numeric', []]]]],
                ],
            ],
        ];
    }
}
