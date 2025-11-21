<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stores zoned date-time as `utc` date-time ONLY, `systemTimezone` will be read from the system
 */
#[ORM\Embeddable]
final class UtcWithSystemTimezone extends Utc
{
    public function __construct(DateTimeInterface $zonedDateTime)
    {
        parent::__construct($zonedDateTime);
    }

    public function getSystemTimezone(): DateTimeZone
    {
        return $this->toZonedDateTime()->getTimezone();
    }

    public function toZonedDateTime(): DateTimeImmutable
    {
        return ZonedDateTimePersistence::computeZonedDateTime($this->getUtcDateTime());
    }
}
