<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

final class LocalDateTimeWithUtcCompanionTest extends TestCase
{
    public function testConstructsItselfFromZonedDateTime(): void
    {
        $localDateTimeWithUtcCompanion = new LocalDateTimeWithUtcCompanion($this->zonedDateTime);

        self::assertDateTimeEquals(
            $this->localDateTime,
            $localDateTimeWithUtcCompanion->localDateTime,
            "Incorrect local date-time",
        );
        self::assertDateTimeEquals(
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $localDateTimeWithUtcCompanion->utcCompanion,
            "Incorrect UTC companion",
        );
    }

    public function testToZonedDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            (new LocalDateTimeWithUtcCompanion($this->localDateTime, JavaSe8\Time::toLocalDateTime($this->utcDateTime)))->toZonedDateTime(),
        );
    }
}
