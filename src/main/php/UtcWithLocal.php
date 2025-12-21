<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

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
    protected DateTimeImmutable|null $local;

    public function __construct(DateTimeInterface $zonedDateTime)
    {
        parent::__construct($zonedDateTime);
        $this->local = JavaSe8\Time::toLocalDateTime(JavaSe8\Time::zonedDateTime($zonedDateTime));
    }

    public static function fromFormattedValues(
        string|null $utcDateTime,
        string|null $localDateTime,
        string $dateTimeFormat,
    ): UtcWithLocal|null {
        return self::fromValues(
            $utcDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($utcDateTime, $dateTimeFormat) : null,
            $localDateTime !== null ? DateTimeUtils::parseAsLocalDateTime($localDateTime, $dateTimeFormat) : null,
        );
    }

    public static function fromValues(
        DateTimeInterface|null $utcDateTime,
        DateTimeInterface|null $localDateTime,
    ): UtcWithLocal|null {
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
