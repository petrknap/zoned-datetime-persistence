<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stores zoned date-time as `utc` date-time with `timezone` identifier
 */
#[ORM\Embeddable]
final class UtcWithTimezone extends Utc
{
    #[ORM\Column(length: 64, nullable: true)]
    protected string|null $timezone;

    /**
     * @param ($dateTimeFormat is null ? DateTimeInterface|null : string|null) $utcDateTime
     */
    public static function ofValues(
        DateTimeInterface|string|null $utcDateTime,
        string|null $timezone,
        string|null $dateTimeFormat = null,
    ): UtcWithTimezone|null {
        if ($dateTimeFormat !== null) {
            $utcDateTime = $utcDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($utcDateTime, $dateTimeFormat) : null;
        }

        $zonedDateTime = ZonedDateTimePersistence::computeZonedDateTime(
            $utcDateTime,
            timezone: $timezone !== null ? new DateTimeZone($timezone) : null,
        );

        return $zonedDateTime != null ? new UtcWithTimezone($zonedDateTime) : null;
    }

    public function __construct(DateTimeInterface $zonedDateTime)
    {
        parent::__construct($zonedDateTime);
        $this->timezone = $zonedDateTime->getTimezone()->getName();
    }

    public function getTimezone(): DateTimeZone
    {
        if ($this->timezone === null) {
            $this->thisInstanceShouldBeNull();
        }
        return new DateTimeZone($this->timezone);
    }

    public function toZonedDateTime(): DateTimeImmutable
    {
        return ZonedDateTimePersistence::computeZonedDateTime(
            $this->getUtcDateTime(),
            timezone: $this->getTimezone(),
        );
    }
}
