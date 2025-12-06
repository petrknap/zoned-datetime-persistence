<?php

declare(strict_types=1);

namespace PetrKnap\Persistence\ZonedDateTime;

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
                localDateTime: $this->localDateTime,
            ),
        );
    }

    public function test_computeZonedDateTime_from_utc_and_local_date_time_instances_of_null(): void
    {
        self::assertNull(ZonedDateTimePersistence::computeZonedDateTime(null, localDateTime: null));
    }

    public function test_computeZonedDateTime_from_utc_date_time_and_timezone_instances(): void
    {
        self::assertDateTimeEquals(
            $this->zonedDateTime,
            ZonedDateTimePersistence::computeZonedDateTime(
                JavaSe8\Time::localDateTime($this->utcDateTime),
                timezone: $this->zonedDateTime->getTimezone(),
            ),
        );
    }

    public function test_computeZonedDateTime_from_utc_date_time_and_timezone_instances_of_null(): void
    {
        self::assertNull(ZonedDateTimePersistence::computeZonedDateTime(null, timezone: null));
    }
}
