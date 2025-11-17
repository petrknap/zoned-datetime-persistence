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

    public function __construct(DateTimeInterface $zonedDateTime)
    {
        parent::__construct($zonedDateTime);
        $this->timezone = $zonedDateTime->getTimezone()->getName();
    }

    /**
     * @param ($dateTimeFormat is null ? DateTimeInterface|null : string|null) $utcDateTime
     */
    public static function fromStored(
        DateTimeInterface|string|null $utcDateTime,
        DateTimeZone|string|null $timezone,
        string|null $dateTimeFormat = null,
    ): UtcWithTimezone|null {
        if ($dateTimeFormat !== null) {
            $utcDateTime = $utcDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($utcDateTime, $dateTimeFormat) : null;
        }

        if (!($timezone instanceof DateTimeZone)) {
            $timezone = $timezone !== null ? new DateTimeZone($timezone) : null;
        }

        $zonedDateTime = ZonedDateTimePersistence::computeZonedDateTime(
            $utcDateTime,
            timezone: $timezone,
        );

        return $zonedDateTime != null ? new UtcWithTimezone($zonedDateTime) : null;
    }

    /**
     * @return ($formatted is false ? DateTimeZone : string)
     */
    public function getTimezone(bool $formatted = false): DateTimeZone|string
    {
        if ($this->timezone === null) {
            $this->thisInstanceShouldBeNull();
        }
        return $formatted ? $this->timezone : new DateTimeZone($this->timezone);
    }

    public function toZonedDateTime(): DateTimeImmutable
    {
        return ZonedDateTimePersistence::computeZonedDateTime(
            $this->getUtcDateTime(),
            timezone: $this->getTimezone(),
        );
    }
}
