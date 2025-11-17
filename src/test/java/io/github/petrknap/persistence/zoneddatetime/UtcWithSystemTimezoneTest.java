package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.junit.jupiter.api.Test;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.*;

final class UtcWithSystemTimezoneTest extends UtcTestCase
{
    @Override @Test void constructs_itself()
    {
        assertInstance(
                getInstance(zonedDateTime),
                utcDateTime.toLocalDateTime()
        );
    }

    @Override @Test void fromValues()
    {
        assertInstance(
                UtcWithSystemTimezone.fromValue(
                        utcDateTime.toLocalDateTime()
                ),
                utcDateTime.toLocalDateTime()
        );
    }

    @Override @Test void fromValues_of_null()
    {
        assertNull(UtcWithSystemTimezone.fromValue(null));
    }

    @Override @Test void fromFormattedValues()
    {
        assertInstance(
                UtcWithSystemTimezone.fromFormattedValue(
                        localDateTimeFormatter.format(utcDateTime),
                        LOCAL_DATETIME_FORMAT
                ),
                utcDateTime.toLocalDateTime()
        );
    }

    @Override @Test void fromFormattedValues_of_null()
    {
        assertNull(UtcWithSystemTimezone.fromFormattedValue(null, LOCAL_DATETIME_FORMAT));
    }

    @Override protected @NotNull UtcWithSystemTimezone getInstance(@NotNull ZonedDateTime zonedDateTime)
    {
        return new UtcWithSystemTimezone(zonedDateTime);
    }

    private static void assertInstance(
            @Nullable UtcWithSystemTimezone actual,
            @NotNull LocalDateTime expectedUtcDateTime
    ) {
        assertNotNull(actual);
        assertEquals(
                expectedUtcDateTime,
                actual.getUtcDateTime(),
                "Unexpected UTC date-time"
        );
    }
}
