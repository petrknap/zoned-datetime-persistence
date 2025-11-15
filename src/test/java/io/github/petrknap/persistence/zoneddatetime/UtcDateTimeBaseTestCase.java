package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.Test;

import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.assertEquals;

abstract class UtcDateTimeBaseTestCase extends TestCase {
    @Test abstract void constructs_itself();

    @Test void getUtcDateTime_as_formatted_string() {
        assertEquals(
                localDateTimeFormatter.format(utcDateTime),
                getInstance(zonedDateTime).getUtcDateTime(LOCAL_DATETIME_FORMAT)
        );
    }

    @Test void toZonedDateTime() {
        assertEquals(
                zonedDateTime,
                getInstance(zonedDateTime).toZonedDateTime()
        );
    }

    abstract protected UtcDateTimeBase getInstance(ZonedDateTime zonedDateTime);
}
