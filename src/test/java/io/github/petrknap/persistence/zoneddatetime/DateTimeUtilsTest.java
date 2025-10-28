package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

final class DateTimeUtilsTest extends TestCase {
    @Test void asUtcInstantAtOffset() {
        assertEquals(
                utcDateTime.plusSeconds(ZONED_DATETIME_OFFSET).toInstant(),
                DateTimeUtils.asUtcInstantAtOffset(localDateTime, ZONED_DATETIME_OFFSET).toInstant()
        );
    }

    @Test void parseAsLocalDateTime() {
        assertEquals(localDateTime, DateTimeUtils.parseAsLocalDateTime(LOCAL_DATETIME, LOCAL_DATETIME_PATTERN));
    }

    @Test void parseAsLocalDateTime_throws_on_incorrect_text() {
        assertThrows(
                DateTimeUtils.Exception.CouldNotParseAsLocalDateTime.class,
                () -> DateTimeUtils.parseAsLocalDateTime("this is not a date", LOCAL_DATETIME_PATTERN)
        );
    }

    @Test void secondsBetween() {
        assertEquals(ZONED_DATETIME_OFFSET, DateTimeUtils.secondsBetween(utcDateTime.toLocalDateTime(), localDateTime));
    }
}
