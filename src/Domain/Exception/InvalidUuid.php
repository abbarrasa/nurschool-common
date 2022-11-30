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

namespace Nurschool\Common\Domain\Exception;

final class InvalidUuid extends Exception
{
    public static function createFromId(string $id): self
    {
        return new self(\sprintf('"%s" is not a valid uuid', $id));
    }
}