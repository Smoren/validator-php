<?php

declare(strict_types=1);

namespace Smoren\Validator\Tests\Unit;

use Codeception\Test\Unit;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Rules\IntegerRule;

class FirstTest extends Unit
{
    /**
     * @dataProvider dataProviderForSuccess
     * @param array $input
     * @return void
     */
    public function testSuccess($input, IntegerRuleInterface $rule): void
    {
        $rule->validate($input);
        $this->assertTrue(true);
    }

    public function dataProviderForSuccess(): array
    {
        return [
            [
                1,
                new IntegerRule(),
            ],
            [
                8,
                (new IntegerRule())
                    ->even()
                    ->positive()
                    ->inInterval(5, 10)
            ],
        ];
    }
}
