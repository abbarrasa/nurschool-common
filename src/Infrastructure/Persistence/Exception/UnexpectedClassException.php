<?php

namespace Nurschool\Common\Infrastructure\Persistence\Exception;

use UnexpectedValueException;

class UnexpectedClassException extends UnexpectedValueException
{
    public static function create($value, $expectedClass): self
    {
        return new self(
            sprintf(
                'Expected class "%s", "%s" given',
                $expectedClass,
                \is_object($value) ? \get_class($value) : \gettype($value)
            )
        );
    }
}
