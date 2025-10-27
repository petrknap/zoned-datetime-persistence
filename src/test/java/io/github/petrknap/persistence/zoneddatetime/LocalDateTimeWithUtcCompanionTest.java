package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertAll;
import static org.junit.jupiter.api.Assertions.assertEquals;

class LocalDateTimeWithUtcCompanionTest extends TestCase {
    @Test void constructs_itself_from_ZonedDateTime() {
        LocalDateTimeWithUtcCompanion localDateTimeWithUtcCompanion = new LocalDateTimeWithUtcCompanion(zonedDateTime);

        assertAll(
                () -> assertEquals(
                        localDateTime,
                        localDateTimeWithUtcCompanion.localDateTime,
                        "Incorrect local date-time"
                ),
                () -> assertEquals(
                        utcDateTime.toLocalDateTime(),
                        localDateTimeWithUtcCompanion.utcCompanion,
                        "Incorrect UTC companion"
                )
        );
    }

    @Test void toZonedDateTime() {
        assertEquals(zonedDateTime, new LocalDateTimeWithUtcCompanion(localDateTime, utcDateTime.toLocalDateTime()).toZonedDateTime());
    }
}
