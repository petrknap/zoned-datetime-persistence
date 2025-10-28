package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertEquals;

final class ZonedDateTimePersistenceTest extends TestCase {
    @Test void computeUtcCompanion() {
        assertEquals(
                utcDateTime.toLocalDateTime(),
                ZonedDateTimePersistence.computeUtcCompanion(zonedDateTime)
        );
    }

    @Test void computeZonedDateTime_from_LocalDateTimes() {
        assertEquals(
                zonedDateTime,
                ZonedDateTimePersistence.computeZonedDateTime(
                        localDateTime,
                        utcDateTime.toLocalDateTime()
                )
        );
    }

    @Test void computeZonedDateTime_from_Strings() {
        assertEquals(
                zonedDateTime,
                ZonedDateTimePersistence.computeZonedDateTime(
                        LOCAL_DATETIME,
                        utcDateTime.toLocalDateTime().format(localDateTimeFormatter),
                        LOCAL_PATTERN
                )
        );
    }
}
