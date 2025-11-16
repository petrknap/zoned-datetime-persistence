package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertEquals;

final class ZonedDateTimePersistenceTest extends TestCase {
    @Test void computeUtcDateTime() {
        assertEquals(
                utcDateTime.toLocalDateTime(),
                ZonedDateTimePersistence.computeUtcDateTime(zonedDateTime)
        );
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
}
