package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.junit.jupiter.api.Test;

import java.time.ZoneId;
import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.*;

final class UtcWithSystemTimezoneTest extends UtcTestCase {
    @Override @Test void constructs_itself() {
        UtcWithSystemTimezone utcWithSystemTimezone = getInstance(zonedDateTime);

        assertAll(
                () -> assertEquals(
                        utcDateTime.toLocalDateTime(),
                        utcWithSystemTimezone.getUtcDateTime(),
                        "Incorrect UTC date-time"
                ),
                () -> assertEquals(
                        ZoneId.systemDefault().normalized(),
                        utcWithSystemTimezone.getSystemTimezone(),
                        "Incorrect system timezone"
                )
        );
    }

    @Override protected @NotNull UtcWithSystemTimezone getInstance(@NotNull ZonedDateTime zonedDateTime) {
        return new UtcWithSystemTimezone(zonedDateTime);
    }
}
