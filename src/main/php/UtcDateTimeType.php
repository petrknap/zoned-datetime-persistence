<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\DBAL\Types\Exception\InvalidType;

/**
 * Converts zoned date-time into UTC date-time
 */
final class UtcDateTimeType extends DateTimeImmutableType
{
    public const NAME = 'utc_datetime';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|null
    {
        if ($value !== null && !($value instanceof DateTimeInterface)) {
            /**
             * @todo remove following fallback for DBAL 3
             *
             * @phpstan-ignore-next-line
             */
            if (!method_exists(InvalidType::class, 'new')) {
                throw ConversionException::conversionFailedInvalidType(
                    $value,
                    self::NAME,
                    ['null', DateTimeInterface::class],
                );
            }
            throw InvalidType::new(
                $value,
                self::NAME,
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
