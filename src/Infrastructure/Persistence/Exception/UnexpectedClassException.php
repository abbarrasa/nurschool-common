<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
