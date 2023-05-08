<?php

declare(strict_types=1);

namespace Smoren\Validator\Factories\Checks;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Helpers\ContainerAccessHelper;
use Smoren\Validator\Helpers\TypeHelper;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Interfaces\IntegerRuleInterface;
use Smoren\Validator\Interfaces\MixedRuleInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class ContainerCheckFactory
{
    public static function getNumericCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::CONTAINER)
            ->withPredicate(fn ($value) => \is_array($value) || \is_object($value))
            ->build();
    }

    public static function getArrayCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::ARRAY)
            ->withPredicate(fn ($value) => \is_array($value))
            ->build();
    }

    public static function getIndexedArrayCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::INDEXED_ARRAY)
            ->withPredicate(fn ($value) => \array_values($value) === $value)
            ->withDependOnChecks([ContainerCheckFactory::getArrayCheck()])
            ->build();
    }

    public static function getAssociativeArrayCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::ASSOCIATIVE_ARRAY)
            ->withPredicate(fn ($value) => \array_values($value) !== $value)
            ->withDependOnChecks([ContainerCheckFactory::getArrayCheck()])
            ->build();
    }

    public static function getArrayAccessibleCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::ARRAY_ACCESSIBLE)
            ->withPredicate(fn ($value) => \is_array($value) || $value instanceof \ArrayAccess)
            ->build();
    }

    public static function getObjectCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::OBJECT)
            ->withPredicate(fn ($value) => \is_object($value))
            ->build();
    }

    public static function getStdObjectCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::STD_OBJECT)
            ->withPredicate(fn ($value) => $value instanceof \stdClass)
            ->build();
    }

    public static function getInstanceOfCheck(string $class): CheckInterface
    {
        return CheckBuilder::create(CheckName::INSTANCE_OF)
            ->withPredicate(fn ($value) => $value instanceof $class)
            ->withCalculatedParams([Param::GIVEN_TYPE => fn ($value) => TypeHelper::getType($value)])
            ->build();
    }

    public static function getCountableCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::COUNTABLE)
            ->withPredicate(fn ($value) => \is_countable($value))
            ->build();
    }

    public static function getIterableCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::ITERABLE)
            ->withPredicate(fn ($value) => \is_iterable($value))
            ->build();
    }

    public static function getEmptyCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::EMPTY)
            ->withPredicate(fn ($value) => \count($value) === 0)
            ->withDependOnChecks([ContainerCheckFactory::getCountableCheck()])
            ->build();
    }

    public static function getNotEmptyCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NOT_EMPTY)
            ->withPredicate(fn ($value) => \count($value) > 0)
            ->withDependOnChecks([ContainerCheckFactory::getCountableCheck()])
            ->build();
    }

    public static function getLengthIsCheck(IntegerRuleInterface $rule): CheckInterface
    {
        return CheckBuilder::create(CheckName::LENGTH_IS)
            ->withPredicate(static function ($value) use ($rule) {
                /** @var \Countable $value */
                $rule->validate(\count($value));
                return true;
            })
            ->withDependOnChecks([ContainerCheckFactory::getCountableCheck()])
            ->build();
    }

    public static function getHasAttributeCheck(string $name): CheckInterface
    {
        return CheckBuilder::create(CheckName::HAS_ATTRIBUTE)
            ->withPredicate(fn ($value) => ContainerAccessHelper::hasAccessibleAttribute($value, $name))
            ->withParams([Param::ATTRIBUTE => $name])
            ->build();
    }

    public static function getHasOptionalAttributeCheck(string $name, MixedRuleInterface $rule): CheckInterface
    {
        return CheckBuilder::create(CheckName::ATTRIBUTE_IS)
            ->withPredicate(static function ($value, string $name) use ($rule) {
                if (!ContainerAccessHelper::hasAccessibleAttribute($value, $name)) {
                    return true;
                }
                $rule->validate(ContainerAccessHelper::getAttributeValue($value, $name));
                return true;
            })
            ->withParams([Param::ATTRIBUTE => $name])
            ->build();
    }

    public static function getAttributeIsCheck(string $name, MixedRuleInterface $rule): CheckInterface
    {
        return CheckBuilder::create(CheckName::ATTRIBUTE_IS)
            ->withPredicate(static function ($value, string $name) use ($rule) {
                $rule->validate(ContainerAccessHelper::getAttributeValue($value, $name));
                return true;
            })
            ->withParams([Param::ATTRIBUTE => $name])
            ->withDependOnChecks([ContainerCheckFactory::getHasAttributeCheck($name)])
            ->build();
    }

    public static function getHasIndexCheck(int $index): CheckInterface
    {
        return CheckBuilder::create(CheckName::HAS_INDEX)
            ->withPredicate(fn ($value, $index) => isset($value[$index]))
            ->withParams([Param::INDEX => $index])
            ->withDependOnChecks([ContainerCheckFactory::getArrayAccessibleCheck()])
            ->build();
    }

    public static function getValueByIndexIsCheck(int $index, MixedRuleInterface $rule): CheckInterface
    {
        return CheckBuilder::create(CheckName::VALUE_BY_INDEX_IS)
            ->withPredicate(static function ($value, int $index) use ($rule) {
                $rule->validate($value[$index]);
                return true;
            })
            ->withParams([Param::INDEX => $index])
            ->withDependOnChecks([ContainerCheckFactory::getHasIndexCheck($index)])
            ->build();
    }

    public static function getAllKeysAreCheck(MixedRuleInterface $rule): CheckInterface
    {
        return CheckBuilder::create(CheckName::ALL_KEYS_ARE)
            ->withPredicate(static function ($value) use ($rule) {
                foreach ($value as $k => $v) {
                    $rule->validate($k);
                }
                return true;
            })
            ->withDependOnChecks([ContainerCheckFactory::getIterableCheck()])
            ->build();
    }

    public static function getAllValuesAreCheck(MixedRuleInterface $rule): CheckInterface
    {
        return CheckBuilder::create(CheckName::ALL_VALUES_ARE)
            ->withPredicate(static function ($value) use ($rule) {
                foreach ($value as $v) {
                    $rule->validate($v);
                }
                return true;
            })
            ->withDependOnChecks([ContainerCheckFactory::getIterableCheck()])
            ->build();
    }
}
