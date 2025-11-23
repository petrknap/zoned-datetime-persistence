package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNull;

final class ZonedDateTimePersistenceTest extends TestCase
{
    @Test void computeUtcDateTime_from_zoned_date_time_instance()
    {
        assertEquals(
                utcDateTime.toLocalDateTime(),
                ZonedDateTimePersistence.computeUtcDateTime(zonedDateTime)
        );
    }

    @Test void computeUtcDateTime_from_zoned_date_time_instance_of_null()
    {
        ZonedDateTime zonedDateTime = null;

        assertNull(ZonedDateTimePersistence.computeUtcDateTime(zonedDateTime));
    }

    @Test void computeZonedDateTime_from_utc_and_local_date_time_instances()
    {
        assertEquals(
                zonedDateTime,
                ZonedDateTimePersistence.computeZonedDateTime(
                        utcDateTime.toLocalDateTime(),
                        localDateTime
                )
        );
    }

    @Test void computeZonedDateTime_from_utc_and_local_date_time_instances_of_null()
    {
        LocalDateTime utcDateTime = null;
        LocalDateTime localDateTime = null;

        assertNull(ZonedDateTimePersistence.computeZonedDateTime(utcDateTime, localDateTime));
    }

    @Test void computeZonedDateTime_from_utc_date_time_and_timezone_instances()
    {
        assertEquals(
                zonedDateTime,
                ZonedDateTimePersistence.computeZonedDateTime(
                        utcDateTime.toLocalDateTime(),
                        zonedDateTime.getZone()
                )
        );
    }

    @Test void computeZonedDateTime_from_utc_date_time_and_timezone_instances_of_null()
    {
        LocalDateTime utcDateTime = null;
        ZoneId timezone = null;

        assertNull(ZonedDateTimePersistence.computeZonedDateTime(utcDateTime, timezone));
    }
}
