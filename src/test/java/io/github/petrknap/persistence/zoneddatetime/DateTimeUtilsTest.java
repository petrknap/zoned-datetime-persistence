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
        assertEquals(localDateTime, DateTimeUtils.parseAsLocalDateTime(LOCAL_DATETIME, LOCAL_DATETIME_FORMAT));
    }

    @Test void parseAsLocalDateTime_throws_on_incorrect_datetime() {
        assertThrows(
                DateTimeUtils.Exception.CouldNotParseAsLocalDateTime.class,
                () -> DateTimeUtils.parseAsLocalDateTime("this is not a local date-time", LOCAL_DATETIME_FORMAT)
        );
    }

    @Test void secondsBetween() {
        assertEquals(ZONED_DATETIME_OFFSET, DateTimeUtils.secondsBetween(utcDateTime.toLocalDateTime(), localDateTime));
    }
}
