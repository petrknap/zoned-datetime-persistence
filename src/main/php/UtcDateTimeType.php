<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;

/**
 * Converts zoned date-time into UTC date-time
 */
final class UtcDateTimeType extends DateTimeImmutableType
{
    public const NAME = 'utc_datetime';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|null
    {
        if ($value !== null && !($value instanceof DateTimeInterface)) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                $this->getName(),
                ['null', DateTimeInterface::class],
            );
        }

        return parent::convertToDatabaseValue(ZonedDateTimePersistence::computeUtcDateTime($value), $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): DateTimeImmutable|null
    {
        return ZonedDateTimePersistence::computeZonedDateTime(
            parent::convertToPHPValue($value, $platform),
            timezone: new DateTimeZone('UTC'),
        );
    }
}
