package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import java.time.LocalDateTime;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNull;

final class ZonedDateTimePersistenceTest extends TestCase {
    @Test void computeUtcDateTime_from_zoned_date_time_instance() {
        assertEquals(
                utcDateTime.toLocalDateTime(),
                ZonedDateTimePersistence.computeUtcDateTime(zonedDateTime)
        );
    }

    @Test void computeUtcDateTime_from_zoned_date_time_instance_of_null() {
        assertNull(ZonedDateTimePersistence.computeUtcDateTime(null));
    }

    @Test void computeZonedDateTime_from_utc_date_time_instance() {
        assertEquals(
                zonedDateTime,
                ZonedDateTimePersistence.computeZonedDateTime(
                        utcDateTime.toLocalDateTime()
                )
        );
    }

    @Test void computeZonedDateTime_from_utc_date_time_instance_of_null() {
        assertNull(ZonedDateTimePersistence.computeZonedDateTime(null));
    }

    @Test void computeZonedDateTime_from_utc_date_time_string() {
        assertEquals(
                zonedDateTime,
                ZonedDateTimePersistence.computeZonedDateTime(
                        utcDateTime.toLocalDateTime().format(localDateTimeFormatter),
                        LOCAL_DATETIME_FORMAT
                )
        );
    }

    @Test void computeZonedDateTime_from_utc_date_time_string_of_null() {
        assertNull(ZonedDateTimePersistence.computeZonedDateTime(null, LOCAL_DATETIME_FORMAT));
    }

    @Test void computeZonedDateTime_from_utc_and_local_date_time_instances() {
        assertEquals(
                zonedDateTime,
                ZonedDateTimePersistence.computeZonedDateTime(
                        utcDateTime.toLocalDateTime(),
                        localDateTime
                )
        );
    }

    @Test void computeZonedDateTime_from_utc_and_local_date_time_instances_of_null() {
        LocalDateTime utc = null;
        LocalDateTime local = null;
        assertNull(ZonedDateTimePersistence.computeZonedDateTime(utc, local));
    }

    @Test void computeZonedDateTime_from_utc_and_local_date_time_strings() {
        assertEquals(
                zonedDateTime,
                ZonedDateTimePersistence.computeZonedDateTime(
                        utcDateTime.toLocalDateTime().format(localDateTimeFormatter),
                        LOCAL_DATETIME,
                        LOCAL_DATETIME_FORMAT
                )
        );
    }

    @Test void computeZonedDateTime_from_utc_and_local_date_time_strings_of_null() {
        assertNull(ZonedDateTimePersistence.computeZonedDateTime(null, null, LOCAL_DATETIME_FORMAT));
    }
}
