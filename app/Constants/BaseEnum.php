<?php

namespace App\Enums;


use App\Exceptions\NotSupportedException;
use Error;
use ReflectionClass;
use ReflectionClassConstant;

abstract class BaseEnum
{
    const NONE = null;

    /**
     * @throws NotSupportedException
     */
    final  private function __construct()
    {
        throw new Error('Constructor not supported for this class');
    }

    /**
     * @throws NotSupportedException
     */
    final private function __clone()
    {
        throw new Error('Cloning not supported for this class');
    }

    final public static function toArray(): array
    {
        return (new ReflectionClass(static::class))->getConstants(ReflectionClassConstant::IS_PUBLIC);
    }

    final public static function isValid(string $value): bool
    {
        return array_key_exists($value, static::toArray());
    }
}
