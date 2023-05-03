<?php

declare(strict_types=1);

namespace Smoren\Validator\Factories\Checks;

use Smoren\Validator\Factories\CheckBuilder;
use Smoren\Validator\Interfaces\CheckInterface;
use Smoren\Validator\Structs\CheckName;
use Smoren\Validator\Structs\Param;

class NumericCheckFactory
{
    public static function getNumericCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NUMERIC)
            ->withPredicate(fn ($value) => \is_numeric($value))
            ->build();
    }

    public static function getNumberCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NUMBER)
            ->withPredicate(fn ($value) => \is_int($value) || \is_float($value))
            ->build();
    }

    public static function getStringCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::STRING)
            ->withPredicate(fn ($value) => \is_string($value))
            ->build();
    }

    public static function getIntegerCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::INTEGER)
            ->withPredicate(fn ($value) => \is_int($value))
            ->build();
    }

    public static function getFloatCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::FLOAT)
            ->withPredicate(fn ($value) => \is_float($value))
            ->build();
    }

    public static function getTruthyCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::TRUTHY)
            ->withPredicate(fn ($value) => \boolval(floatval($value)))
            ->build();
    }

    public static function getFalsyCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::FALSY)
            ->withPredicate(fn ($value) => !\boolval(floatval($value)))
            ->build();
    }

    public static function getPositiveCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::POSITIVE)
            ->withPredicate(fn($value) => $value > 0)
            ->build();
    }

    public static function getNonPositiveCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NON_POSITIVE)
            ->withPredicate(fn($value) => $value <= 0)
            ->build();
    }

    public static function getNonNegativeCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NON_NEGATIVE)
            ->withPredicate(fn($value) => $value >= 0)
            ->build();
    }

    public static function getNegativeCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NEGATIVE)
            ->withPredicate(fn($value) => $value < 0)
            ->build();
    }

    /**
     * @param numeric $number
     * @return CheckInterface
     */
    public static function getGreaterThanCheck($number): CheckInterface
    {
        return CheckBuilder::create(CheckName::GREATER)
            ->withPredicate(fn($value, $number) => $value > $number)
            ->withParams([Param::EXPECTED => $number])
            ->build();
    }

    /**
     * @param numeric $number
     * @return CheckInterface
     */
    public static function getGreaterOrEqualCheck($number): CheckInterface
    {
        return CheckBuilder::create(CheckName::GREATER_OR_EQUEAL)
            ->withPredicate(fn($value, $number) => $value >= $number)
            ->withParams([Param::EXPECTED => $number])
            ->build();
    }

    /**
     * @param numeric $number
     * @return CheckInterface
     */
    public static function getLessThanCheck($number): CheckInterface
    {
        return CheckBuilder::create(CheckName::LESS)
            ->withPredicate(fn($value, $number) => $value < $number)
            ->withParams([Param::EXPECTED => $number])
            ->build();
    }

    /**
     * @param numeric $number
     * @return CheckInterface
     */
    public static function getLessOrEqualCheck($number): CheckInterface
    {
        return CheckBuilder::create(CheckName::LESS_OR_EQUEAL)
            ->withPredicate(fn($value, $number) => $value <= $number)
            ->withParams([Param::EXPECTED => $number])
            ->build();
    }

    /**
     * @param numeric $start
     * @param numeric $end
     * @return CheckInterface
     */
    public static function getBetweenCheck($start, $end): CheckInterface
    {
        return CheckBuilder::create(CheckName::BETWEEN)
            ->withPredicate(fn($value, $start, $end) => $value >= $start && $value <= $end)
            ->withParams(['start' => $start, 'end' => $end])
            ->build();
    }

    /**
     * @param numeric $start
     * @param numeric $end
     * @return CheckInterface
     */
    public static function getInOpenIntervalCheck($start, $end): CheckInterface
    {
        return CheckBuilder::create(CheckName::IN_OPEN_INTERVAL)
            ->withPredicate(fn($value, $start, $end) => $value > $start && $value < $end)
            ->withParams(['start' => $start, 'end' => $end])
            ->build();
    }

    /**
     * @param numeric $start
     * @param numeric $end
     * @return CheckInterface
     */
    public static function getInLeftOpenIntervalCheck($start, $end): CheckInterface
    {
        return CheckBuilder::create(CheckName::IN_LEFT_HALF_OPEN_INTERVAL)
            ->withPredicate(fn($value, $start, $end) => $value > $start && $value <= $end)
            ->withParams(['start' => $start, 'end' => $end])
            ->build();
    }

    /**
     * @param numeric $start
     * @param numeric $end
     * @return CheckInterface
     */
    public static function getInRightOpenIntervalCheck($start, $end): CheckInterface
    {
        return CheckBuilder::create(CheckName::IN_RIGHT_HALF_OPEN_INTERVAL)
            ->withPredicate(fn($value, $start, $end) => $value >= $start && $value < $end)
            ->withParams(['start' => $start, 'end' => $end])
            ->build();
    }

    public static function getFractionalCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::FRACTIONAL)
            ->withPredicate(fn($value) => \abs($value - \round(\floatval($value))) >= \PHP_FLOAT_EPSILON)
            ->build();
    }

    public static function getNonFractionalCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NON_FRACTIONAL)
            ->withPredicate(fn($value) => \abs($value - \round(\floatval($value))) < \PHP_FLOAT_EPSILON)
            ->build();
    }

    public static function getFiniteCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::FINITE)
            ->withPredicate(fn($value) => $value > -INF && $value < INF)
            ->build();
    }

    public static function getInfiniteCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::INFINITE)
            ->withPredicate(fn($value) => $value === -INF || $value === INF)
            ->build();
    }

    public static function getEvenCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::EVEN)
            ->withPredicate(fn($value) => $value % 2 === 0)
            ->build();
    }

    public static function getOddCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::ODD)
            ->withPredicate(fn($value) => $value % 2 !== 0)
            ->build();
    }

    public static function getNanCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NAN)
            ->withPredicate(fn($value) => \is_nan(\floatval($value)))
            ->withDependOnChecks([NumericCheckFactory::getNumericCheck()])
            ->build();
    }

    public static function getNotNanCheck(): CheckInterface
    {
        return CheckBuilder::create(CheckName::NOT_NAN)
            ->withPredicate(fn($value) => !\is_nan(\floatval($value)))
            ->withDependOnChecks([NumericCheckFactory::getNumericCheck()])
            ->build();
    }
}
