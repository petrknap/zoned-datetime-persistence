<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @phpstan-import-type LocalDateTime from JavaSe8\Time
 * @phpstan-import-type ZonedDateTime from JavaSe8\Time
 */
#[ORM\Embeddable]
final class UtcWithLocal extends Utc
{
    /**
     * @var LocalDateTime|null
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    protected readonly DateTimeImmutable|null $local;

    public function __construct(
        DateTimeInterface $zonedDateTime,
    ) {
        parent::__construct($zonedDateTime);
        $this->local = JavaSe8\Time::toLocalDateTime(JavaSe8\Time::zonedDateTime($zonedDateTime));
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

    /**
     * @return ZonedDateTime
     */
    public function toZonedDateTime(): DateTimeImmutable
    {
        return ZonedDateTimePersistence::computeZonedDateTime(
            $this->getUtcDateTime(),
            $this->getLocalDateTime(),
        );
    }
}
