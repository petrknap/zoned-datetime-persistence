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

    public static function fromFormattedValues(
        string|null $utcDateTime,
        string $dateTimeFormat,
        string|null $timezone,
    ): UtcWithTimezone|null {
        return self::fromValues(
            $utcDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($utcDateTime, $dateTimeFormat) : null,
            $timezone !== null ? new DateTimeZone($timezone) : null,
        );
    }

    public static function fromValues(
        DateTimeInterface|null $utcDateTime,
        DateTimeZone|null $timezone,
    ): UtcWithTimezone|null {
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
