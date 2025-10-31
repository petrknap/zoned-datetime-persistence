<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 *
 * @phpstan-type Constructed array{0: LocalDateTime, 1: LocalDateTime}
 */
#[ORM\Embeddable]
final class LocalDateTimeWithUtcCompanion
{
    /**
     * @var LocalDateTime
     */
    #[ORM\Column(type: 'datetime_immutable')]
    protected readonly DateTimeImmutable $local;
    /**
     * @var LocalDateTime
     */
    #[ORM\Column(type: 'datetime_immutable')]
    protected readonly DateTimeImmutable $utc;

    public function __construct(
        DateTimeInterface $dateTime,
        DateTimeInterface|null $utcCompanion = null,
    ) {
        if ($utcCompanion === null) {
            [$this->local, $this->utc] = self::constructFromZonedDateTime($dateTime);
        } elseif ($dateTime->getOffset() === $utcCompanion->getOffset()) {
            [$this->local, $this->utc] = self::constructFromLocalDateTimes($dateTime, $utcCompanion);
        } else {
            throw new InvalidArgumentException('Arguments must be zoned $dateTime, or local $dateTime and $utcCompanion');
        }
    }

    /**
     * @return ($format is false ? LocalDateTime : string)
     */
    public function getLocalDateTime(string|false $format = false): DateTimeImmutable|string
    {
        return $format ? $this->local->format($format) : $this->local;
    }

    /**
     * @return ($format is false ? LocalDateTime : string)
     */
    public function getUtcCompanion(string|false $format = false): DateTimeImmutable|string
    {
        return $format ? $this->utc->format($format) : $this->utc;
    }

    /**
     * @return ZonedDateTime
     */
    public function toZonedDateTime(): DateTimeImmutable
    {
        return ZonedDateTimePersistence::computeZonedDateTime($this->local, $this->utc);
    }

    /**
     * @return Constructed
     */
    private function constructFromLocalDateTimes(DateTimeInterface $dateTime, DateTimeInterface $utcCompanion): array
    {
        return [
            JavaSe8\Time::localDateTime($dateTime),
            JavaSe8\Time::localDateTime($utcCompanion),
        ];
    }

    /**
     * @return Constructed
     */
    private function constructFromZonedDateTime(DateTimeInterface $dateTime): array
    {
        $zonedDateTime = JavaSe8\Time::zonedDateTime($dateTime);

        return self::constructFromLocalDateTimes(
            JavaSe8\Time::toLocalDateTime($zonedDateTime),
            ZonedDateTimePersistence::computeUtcCompanion($zonedDateTime),
        );
    }
}
