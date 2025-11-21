<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

use DateTimeInterface;

final class UtcWithSystemTimezoneTest extends UtcTestCase
{
    public function test_constructs_itself(): void
    {
        $utcWithSystemTimezone = $this->getInstance($this->zonedDateTime);

        self::assertDateTimeEquals(
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $utcWithSystemTimezone->getUtcDateTime(),
            'Incorrect UTC date-time',
        );
        self::assertEquals(
            date_default_timezone_get(),
            $utcWithSystemTimezone->getSystemTimezone()->getName(),
            'Incorrect system timezone',
        );
    }

    protected function getInstance(DateTimeInterface $zonedDateTime): UtcWithSystemTimezone
    {
        return new UtcWithSystemTimezone($zonedDateTime);
    }
}
