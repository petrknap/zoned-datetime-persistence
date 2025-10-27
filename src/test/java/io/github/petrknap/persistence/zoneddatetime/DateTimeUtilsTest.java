package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import java.time.ZoneOffset;
import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.assertAll;
import static org.junit.jupiter.api.Assertions.assertEquals;

class DateTimeUtilsTest extends TestCase {
    @Test void asUtcInstantAtOffset() {
        ZonedDateTime zonedDateTime = DateTimeUtils.asUtcInstantAtOffset(localDateTime, OFFSET);

        assertAll(
                () -> assertEquals(
                        localDateTime.plusSeconds(OFFSET),
                        zonedDateTime.toLocalDateTime(),
                        "Local date-times must be shifted by an offset"
                ),
                () -> assertEquals(
                        localDateTime.toInstant(ZoneOffset.UTC),
                        zonedDateTime.toInstant(),
                        "Instants over UTC must be equal"
                )
        );
    }

    @Test void secondsBetween() {
        assertEquals(OFFSET, DateTimeUtils.secondsBetween(utcDateTime.toLocalDateTime(), localDateTime));
    }

    @Test void parseAsLocalDateTime() {
        assertEquals(localDateTime, DateTimeUtils.parseAsLocalDateTime(LOCAL_DATETIME, LOCAL_PATTERN));
    }
}
