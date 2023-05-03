# PHP Validation Tools

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/smoren/validator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Smoren/validator-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Smoren/validator-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Smoren/validator-php/badge.svg?branch=master)](https://coveralls.io/github/Smoren/validator-php?branch=master)
![Build and test](https://github.com/Smoren/validator-php/actions/workflows/test_master.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## How to install to your project
```
composer require smoren/validator
```

## Usage

```php
use Smoren\Validator\Factories\Value;
use Smoren\Validator\Exceptions\ValidationError;

$rule = Value::container()
    ->array()
    ->hasAttribute('id', Value::integer()->positive())
    ->hasAttribute('probability', Value::float()->between(0, 1))
    ->hasAttribute('vectors', Value::container()->array()->allValuesAre(
        Value::container()
            ->array()
            ->lengthIs(Value::integer()->equal(2))
            ->allValuesAre(Value::integer())
    ));

$validInput = [
    'id' => 13,
    'probability' => 0.92,
    'vectors' => [[1, 2], [3, 4], [5, 6]],
];

try {
    $rule->validate($validInput);
} catch (ValidationError $e) {
    // Input is valid so this block is unreachable.
}

$invalidInput = [
    'id' => '13',
    'probability' => 1.92,
    'vectors' => [[1, 2.1], [3, 4], [5, 6]],
];

try {
    $rule->validate($invalidInput);
} catch (ValidationError $e) {
    // Input is invalid so we catch the exception.
    print_r($e->getViolatedRestrictions());
    /*
    [
        ['attribute_is', [
            'attribute' => 'id',
            'rule' => 'integer',
            'violated_restrictions' => [
                ['integer', []]
            ]
        ]],
        ['attribute_is', [
            'attribute' => 'probability',
            'rule' => 'float',
            'violated_restrictions' => [
                ['in_segment', [
                    'start' => 0,
                    'end' => 1
                ]]
            ]
        ]],
        ['attribute_is', [
            'attribute' => 'vectors',
            'rule' => 'container',
            'violated_restrictions' => [
                ['all_values_are', [
                    'rule' => 'container',
                    'violated_restrictions' => [
                        ['all_values_are', [
                            'rule' => 'integer',
                            'violated_restrictions' => [
                                ['integer', []]
                            ]
                        ]]
                    ]
                ]]
            ]
        ]]
    ]
    */
}

```

## Unit testing
```
composer install
composer test-init
composer test
```

## Standards

PHP Validator Tools conforms to the following standards:

* PSR-1 — [Basic coding standard](https://www.php-fig.org/psr/psr-1/)
* PSR-4 — [Autoloader](https://www.php-fig.org/psr/psr-4/)
* PSR-12 — [Extended coding style guide](https://www.php-fig.org/psr/psr-12/)


## License

PHP Validation Tools is licensed under the MIT License.
