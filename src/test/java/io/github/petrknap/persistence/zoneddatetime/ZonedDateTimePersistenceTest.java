package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertEquals;

class ZonedDateTimePersistenceTest extends TestCase {
    @Test void computeUtcCompanion_from_ZonedDateTime() {
        assertEquals(utcDateTime.toLocalDateTime(), ZonedDateTimePersistence.computeUtcCompanion(zonedDateTime));
    }

    @Test void computeZonedDateTime_from_LocalTimes() {
        assertEquals(zonedDateTime, ZonedDateTimePersistence.computeZonedDateTime(
                localDateTime,
                utcDateTime.toLocalDateTime()
        ));
    }

    @Test void computeZonedDateTime_from_CharSequences() {
        assertEquals(zonedDateTime, ZonedDateTimePersistence.computeZonedDateTime(
                LOCAL_DATETIME,
                utcDateTime.format(localDateTimeFormatter),
                LOCAL_PATTERN
        ));
    }
}
