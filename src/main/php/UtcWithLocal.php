<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stores zoned date-time as `utc` date-time with `local` date-time
 *
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 */
#[ORM\Embeddable]
final class UtcWithLocal extends Utc
{
    /**
     * @var LocalDateTime|null
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    protected readonly DateTimeImmutable|null $local;

    public function __construct(DateTimeInterface $zonedDateTime)
    {
        parent::__construct($zonedDateTime);
        $this->local = JavaSe8\Time::toLocalDateTime(JavaSe8\Time::zonedDateTime($zonedDateTime));
    }

    /**
     * @param ($dateTimeFormat is null ? DateTimeInterface|null : string|null) $utcDateTime
     * @param ($dateTimeFormat is null ? DateTimeInterface|null  : string|null) $localDateTime
     */
    public static function fromStored(
        DateTimeInterface|string|null $utcDateTime,
        DateTimeInterface|string|null $localDateTime,
        string|null $dateTimeFormat = null,
    ): UtcWithLocal|null {
        if ($dateTimeFormat !== null) {
            $utcDateTime = $utcDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($utcDateTime, $dateTimeFormat) : null;
            $localDateTime = $localDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($localDateTime, $dateTimeFormat) : null;
        }

        $zonedDateTime = ZonedDateTimePersistence::computeZonedDateTime(
            $utcDateTime,
            localDateTime: $localDateTime,
        );

        return $zonedDateTime !== null ? new UtcWithLocal($zonedDateTime) : null;
    }

    /**
     * @return ($format is false ? LocalDateTime : string)
     */
    public function getLocalDateTime(string|false $format = false): DateTimeImmutable|string
    {
        if ($this->local === null) {
            $this->thisInstanceShouldBeNull();
        }
        return $format ? $this->local->format($format) : $this->local;
    }

    public function toZonedDateTime(): DateTimeImmutable
    {
        return ZonedDateTimePersistence::computeZonedDateTime(
            $this->getUtcDateTime(),
            localDateTime: $this->getLocalDateTime(),
        );
    }
}
