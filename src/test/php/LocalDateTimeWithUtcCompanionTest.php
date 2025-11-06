<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

final class LocalDateTimeWithUtcCompanionTest extends TestCase
{
    public function test_constructs_itself_from_zoned_DateTime(): void
    {
        $localDateTimeWithUtcCompanion = new LocalDateTimeWithUtcCompanion($this->zonedDateTime);

        self::assertDateTimeEquals(
            $this->localDateTime,
            $localDateTimeWithUtcCompanion->getLocalDateTime(),
            "Incorrect local date-time",
        );
        self::assertDateTimeEquals(
            JavaSe8\Time::toLocalDateTime($this->utcDateTime),
            $localDateTimeWithUtcCompanion->getUtcCompanion(),
            "Incorrect UTC companion",
        );
    }

    public function test_getLocalDateTime_as_formatted_string(): void
    {
        self::assertEquals(
            $this->localDateTime->format(self::LOCAL_DATETIME_FORMAT),
            (new LocalDateTimeWithUtcCompanion($this->zonedDateTime))
                ->getLocalDateTime(self::LOCAL_DATETIME_FORMAT),
        );
    }

    public function test_getUtcCompanion_as_formatted_string(): void
    {
        self::assertEquals(
            $this->utcDateTime->format(self::LOCAL_DATETIME_FORMAT),
            (new LocalDateTimeWithUtcCompanion($this->zonedDateTime))
                ->getUtcCompanion(self::LOCAL_DATETIME_FORMAT),
        );
    }

    public function test_toZonedDateTime(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            (new LocalDateTimeWithUtcCompanion($this->localDateTime, JavaSe8\Time::toLocalDateTime($this->utcDateTime)))->toZonedDateTime(),
        );
    }
}
