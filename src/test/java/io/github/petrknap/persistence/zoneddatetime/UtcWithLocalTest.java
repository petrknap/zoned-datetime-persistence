package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.junit.jupiter.api.Test;

import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.assertAll;
import static org.junit.jupiter.api.Assertions.assertEquals;

final class UtcWithLocalTest extends UtcTestCase {
    @Override @Test void constructs_itself() {
        UtcWithLocal utcWithLocal = getInstance(zonedDateTime);

        assertAll(
                () -> assertEquals(
                        utcDateTime.toLocalDateTime(),
                        utcWithLocal.getUtcDateTime(),
                        "Incorrect UTC date-time"
                ),
                () -> assertEquals(
                        localDateTime,
                        utcWithLocal.getLocalDateTime(),
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

    @Override @NotNull UtcWithLocal getInstance(@NotNull ZonedDateTime zonedDateTime) {
        return new UtcWithLocal(zonedDateTime);
    }
}
