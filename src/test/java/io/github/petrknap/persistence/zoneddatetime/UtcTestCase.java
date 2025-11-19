package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.junit.jupiter.api.Test;

import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertSame;

abstract class UtcTestCase extends TestCase {
    abstract @Test void constructs_itself();

    @Test void asNullable_returns_this() {
        Utc instance = getInstance(zonedDateTime);

        assertSame(instance, instance.asNullable());
    }

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

    abstract @NotNull Utc getInstance(@NotNull ZonedDateTime zonedDateTime);
}
