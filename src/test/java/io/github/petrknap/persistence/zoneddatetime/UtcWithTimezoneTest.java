package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.junit.jupiter.api.Test;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.*;
import static org.junit.jupiter.api.Assertions.assertNull;

final class UtcWithTimezoneTest extends UtcTestCase
{
    @Override @Test void constructs_itself()
    {
        assertInstance(
                getInstance(zonedDateTime),
                utcDateTime.toLocalDateTime(),
                zonedDateTime.getZone()
        );
    }

    @Override @Test void ofValues_as_scalars()
    {
        assertInstance(
                UtcWithTimezone.ofValues(
                        localDateTimeFormatter.format(utcDateTime),
                        zonedDateTime.getZone().getId(),
                        LOCAL_DATETIME_FORMAT
                ),
                utcDateTime.toLocalDateTime(),
                zonedDateTime.getZone()
        );
    }

    @Override @Test void ofValues_as_scalars_of_null()
    {
        assertNull(UtcWithTimezone.ofValues(null, null, LOCAL_DATETIME_FORMAT));
    }

    @Override @Test void ofValues_as_embedded()
    {
        assertInstance(
                UtcWithTimezone.ofValues(
                        utcDateTime.toLocalDateTime(),
                        zonedDateTime.getZone().getId()
                ),
                utcDateTime.toLocalDateTime(),
                zonedDateTime.getZone()
        );
    }

    @Override @Test void ofValues_as_embedded_of_null()
    {
        assertNull(UtcWithTimezone.ofValues(null, null));
    }

    @Override protected @NotNull UtcWithTimezone getInstance(@NotNull ZonedDateTime zonedDateTime)
    {
        return new UtcWithTimezone(zonedDateTime);
    }

    private static void assertInstance(
            @Nullable UtcWithTimezone actual,
            @NotNull LocalDateTime expectedUtcDateTime,
            @NotNull ZoneId expectedTimezone
    ) {
        assertNotNull(actual);
        assertAll(
                () -> assertEquals(
                        expectedUtcDateTime,
                        actual.getUtcDateTime(),
                        "Unexpected UTC date-time"
                ),
                () -> assertEquals(
                        expectedTimezone,
                        actual.getTimezone(),
                        "Unexpected timezone"
                )
        );
    }
}
