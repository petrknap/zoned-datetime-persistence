package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.junit.jupiter.api.Test;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.*;

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

    @Override @Test void fromValues()
    {
        assertInstance(
                UtcWithTimezone.fromValues(
                        utcDateTime.toLocalDateTime(),
                        zonedDateTime.getZone()
                ),
                utcDateTime.toLocalDateTime(),
                zonedDateTime.getZone()
        );
    }

    @Override @Test void fromValues_of_null()
    {
        assertNull(UtcWithTimezone.fromValues(null, null));
    }

    @Override @Test void fromFormattedValues()
    {
        assertInstance(
                UtcWithTimezone.fromFormattedValues(
                        localDateTimeFormatter.format(utcDateTime),
                        LOCAL_DATETIME_FORMAT,
                        zonedDateTime.getZone().getId()
                ),
                utcDateTime.toLocalDateTime(),
                zonedDateTime.getZone()
        );
    }

    @Override @Test void fromFormattedValues_of_null()
    {
        assertNull(UtcWithTimezone.fromFormattedValues(null, LOCAL_DATETIME_FORMAT, null));
    }

    @Test void getTimezone_as_formatted_string()
    {
        assertEquals(
                zonedDateTime.getZone().getId(),
                getInstance(zonedDateTime).getTimezone(true)
        );
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
