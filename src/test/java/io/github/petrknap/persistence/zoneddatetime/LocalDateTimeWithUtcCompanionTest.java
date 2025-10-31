package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertAll;
import static org.junit.jupiter.api.Assertions.assertEquals;

final class LocalDateTimeWithUtcCompanionTest extends TestCase {
    @Test void constructs_itself_from_ZonedDateTime() {
        LocalDateTimeWithUtcCompanion localDateTimeWithUtcCompanion = new LocalDateTimeWithUtcCompanion(zonedDateTime);

        assertAll(
                () -> assertEquals(
                        localDateTime,
                        localDateTimeWithUtcCompanion.getLocalDateTime(),
                        "Incorrect local date-time"
                ),
                () -> assertEquals(
                        utcDateTime.toLocalDateTime(),
                        localDateTimeWithUtcCompanion.getUtcCompanion(),
                        "Incorrect UTC companion"
                )
        );
    }

    @Test void getLocalDateTime_as_formatted_string() {
        assertEquals(
                localDateTimeFormatter.format(localDateTime),
                new LocalDateTimeWithUtcCompanion(zonedDateTime)
                        .getLocalDateTime(LOCAL_DATETIME_FORMAT)
        );
    }

    @Test void getUtcCompanion_as_formatted_string() {
        assertEquals(
                localDateTimeFormatter.format(utcDateTime),
                new LocalDateTimeWithUtcCompanion(zonedDateTime)
                        .getUtcCompanion(LOCAL_DATETIME_FORMAT)
        );
    }

    @Test void toZonedDateTime() {
        assertEquals(zonedDateTime, new LocalDateTimeWithUtcCompanion(localDateTime, utcDateTime.toLocalDateTime()).toZonedDateTime());
    }
}
