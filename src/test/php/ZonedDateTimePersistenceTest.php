<?php

declare(strict_types=1);

namespace PetrKnap\ZonedDateTimePersistence;

final class ZonedDateTimePersistenceTest extends TestCase
{
    public function test_computeUtcDateTime_from_zoned_date_time_instance(): void
    {
        self::assertDateTimeEquals(
            JavaSe8\Time::localDateTime($this->utcDateTime),
            ZonedDateTimePersistence::computeUtcDateTime($this->zonedDateTime),
        );
    }

    public function test_computeUtcDateTime_from_zoned_date_time_instance_of_null(): void
    {
        self::assertNull(ZonedDateTimePersistence::computeUtcDateTime(null));
    }

    public function test_computeZonedDateTime_from_utc_and_local_date_time_instances(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            ZonedDateTimePersistence::computeZonedDateTime(
                JavaSe8\Time::localDateTime($this->utcDateTime),
                $this->localDateTime,
            ),
        );
    }

    public function test_computeZonedDateTime_from_utc_and_local_date_time_instances_of_null(): void
    {
        self::assertNull(ZonedDateTimePersistence::computeZonedDateTime(null, null));
    }

    public function test_computeZonedDateTime_from_utc_and_local_date_time_strings(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            ZonedDateTimePersistence::computeZonedDateTime(
                JavaSe8\Time::localDateTime($this->utcDateTime)->format(self::LOCAL_DATETIME_FORMAT),
                self::LOCAL_DATETIME,
                self::LOCAL_DATETIME_FORMAT,
            ),
        );
    }

    public function test_computeZonedDateTime_from_utc_and_local_date_time_strings_of_null(): void
    {
        self::assertNull(ZonedDateTimePersistence::computeZonedDateTime(null, null, self::LOCAL_DATETIME_FORMAT));
    }
}
