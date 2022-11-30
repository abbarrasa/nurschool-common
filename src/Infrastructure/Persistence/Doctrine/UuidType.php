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

namespace Nurschool\Common\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;
use Nurschool\Common\Domain\Exception\InvalidUuid;
use Nurschool\Common\Domain\ValueObject\Uuid;

class UuidType extends GuidType
{
    /**
     * @var string
     */
    private const NAME = 'uuid';

    public function getName()
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Uuid) {
            return (string) $value;
        }

        // It is a valid Uuid
        try {
            $uuid = new Uuid($value);
            return (string) $uuid;
        } catch (InvalidUuid $e) {
            throw ConversionException::conversionFailed($value, static::NAME);
        }
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value;
        }

        try {
            $uuid = new Uuid($value);
            return $uuid;
        } catch (InvalidUuid $e) {
            throw ConversionException::conversionFailed($value, static::NAME);
        }
    }
    
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
    
    public function getMappedDatabaseTypes(AbstractPlatform $platform)
    {
        return [self::NAME];
    }    
}