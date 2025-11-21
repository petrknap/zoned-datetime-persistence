<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeImmutableType;

/**
 * Converts zoned date-time into UTC date-time
 *
 * @note it does not convert values provided to DQL
 *
 * @see UtcWithSystemTimezone
 */
final class UtcDateTimeType extends DateTimeImmutableType
{
    public const NAME = 'utcdatetime';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return parent::convertToDatabaseValue(ZonedDateTimePersistence::computeUtcDateTime($value), $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $localDateTime = parent::convertToPHPValue($value, $platform);
        return ZonedDateTimePersistence::computeZonedDateTime($localDateTime, localDateTime: $localDateTime)?->setTimezone(new DateTimeZone('UTC'));
    }
}
