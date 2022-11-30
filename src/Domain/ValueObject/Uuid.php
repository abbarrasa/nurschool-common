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

namespace Nurschool\Common\Domain\ValueObject;

use Nurschool\Common\Domain\Exception\InvalidUuid;

final class Uuid
{
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);
        $this->value = $value;
    }

    public static function random(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }   

    private function ensureIsValidUuid(string $id): void
    {
        if (!\Ramsey\Uuid\Uuid::isValid($id)) {
            throw InvalidUuid::createFromId($id);
        }
    }    
}