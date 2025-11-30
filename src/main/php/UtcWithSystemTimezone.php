<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stores zoned date-time as `utc` date-time ONLY, system timezone will be read from the system
 */
#[ORM\Embeddable]
final class UtcWithSystemTimezone extends Utc
{
    public function __construct(DateTimeInterface $zonedDateTime)
    {
        parent::__construct($zonedDateTime);
    }

    public static function fromFormattedValue(
        string|null $utcDateTime,
        string $dateTimeFormat,
    ): UtcWithSystemTimezone|null {
        return self::fromValue(
            $utcDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($utcDateTime, $dateTimeFormat) : null,
        );
    }

    public static function fromValue(
        DateTimeInterface|null $utcDateTime,
    ): UtcWithSystemTimezone|null {
        $zonedDateTime = ZonedDateTimePersistence::computeZonedDateTime(
            $utcDateTime,
            timezone: self::systemTimezone(),
        );

        return $zonedDateTime != null ? new UtcWithSystemTimezone($zonedDateTime) : null;
    }

    public function toZonedDateTime(): DateTimeImmutable
    {
        return ZonedDateTimePersistence::computeZonedDateTime(
            $this->getUtcDateTime(),
            timezone: self::systemTimezone(),
        );
    }

    private static function systemTimezone(): DateTimeZone
    {
        return new DateTimeZone(date_default_timezone_get());
    }
}
