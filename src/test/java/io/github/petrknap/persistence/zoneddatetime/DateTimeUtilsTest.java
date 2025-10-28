package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

final class DateTimeUtilsTest extends TestCase {
    @Test void asUtcInstantAtOffset() {
        assertEquals(
                utcDateTime.plusSeconds(OFFSET).toInstant(),
                DateTimeUtils.asUtcInstantAtOffset(localDateTime, OFFSET).toInstant()
        );
    }

    @Test void parseAsLocalDateTime() {
        assertEquals(localDateTime, DateTimeUtils.parseAsLocalDateTime(LOCAL_DATETIME, LOCAL_PATTERN));
    }

    @Test void parseAsLocalDateTime_throws_on_incorrect_text() {
        assertThrows(
                DateTimeUtils.Exception.CouldNotParseAsLocalDateTime.class,
                () -> DateTimeUtils.parseAsLocalDateTime("this is not a date", LOCAL_PATTERN)
        );
    }

    @Test void secondsBetween() {
        assertEquals(OFFSET, DateTimeUtils.secondsBetween(utcDateTime.toLocalDateTime(), localDateTime));
    }
}
