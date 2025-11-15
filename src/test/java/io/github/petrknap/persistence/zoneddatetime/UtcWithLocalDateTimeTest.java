package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.assertAll;
import static org.junit.jupiter.api.Assertions.assertEquals;

final class UtcWithLocalDateTimeTest extends UtcDateTimeBaseTestCase {
    @Override @Test void constructs_itself() {
        UtcWithLocalDateTime utcWithLocalDateTime = getInstance(zonedDateTime);

        assertAll(
                () -> assertEquals(
                        utcDateTime.toLocalDateTime(),
                        utcWithLocalDateTime.getUtcDateTime(),
                        "Incorrect UTC date-time"
                ),
                () -> assertEquals(
                        localDateTime,
                        utcWithLocalDateTime.getLocalDateTime(),
                        "Incorrect local date-time"
                )
        );
    }

    @Test void getLocalDateTime_as_formatted_string() {
        assertEquals(
                localDateTimeFormatter.format(localDateTime),
                getInstance(zonedDateTime).getLocalDateTime(LOCAL_DATETIME_FORMAT)
        );
    }

    @Override protected UtcWithLocalDateTime getInstance(ZonedDateTime zonedDateTime) {
        return new UtcWithLocalDateTime(zonedDateTime);
    }
}
