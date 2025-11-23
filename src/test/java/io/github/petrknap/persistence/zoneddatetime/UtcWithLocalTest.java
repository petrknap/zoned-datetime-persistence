package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.junit.jupiter.api.Test;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.*;

final class UtcWithLocalTest extends UtcTestCase
{
    @Override @Test void constructs_itself()
    {
        assertInstance(
                getInstance(zonedDateTime),
                utcDateTime.toLocalDateTime(),
                localDateTime
        );
    }

    @Override @Test void fromValues()
    {
        assertInstance(
                UtcWithLocal.fromValues(
                        utcDateTime.toLocalDateTime(),
                        localDateTime
                ),
                utcDateTime.toLocalDateTime(),
                localDateTime
        );
    }

    @Override @Test void fromValues_of_null()
    {
        assertNull(UtcWithLocal.fromValues(null, null));
    }

    @Override @Test void fromFormattedValues()
    {
        assertInstance(
                UtcWithLocal.fromFormattedValues(
                        localDateTimeFormatter.format(utcDateTime),
                        LOCAL_DATETIME,
                        LOCAL_DATETIME_FORMAT
                ),
                utcDateTime.toLocalDateTime(),
                localDateTime
        );
    }

    @Override @Test void fromFormattedValues_of_null()
    {
        assertNull(UtcWithLocal.fromFormattedValues(null, null, LOCAL_DATETIME_FORMAT));
    }

    @Test void getLocalDateTime_as_formatted_string()
    {
        assertEquals(
                localDateTimeFormatter.format(localDateTime),
                getInstance(zonedDateTime).getLocalDateTime(LOCAL_DATETIME_FORMAT)
        );
    }

    @Override protected @NotNull UtcWithLocal getInstance(@NotNull ZonedDateTime zonedDateTime)
    {
        return new UtcWithLocal(zonedDateTime);
    }

    private static void assertInstance(
            @Nullable UtcWithLocal actual,
            @NotNull LocalDateTime expectedUtcDateTime,
            @NotNull LocalDateTime expectedLocalDateTime
    ) {
        assertNotNull(actual);
        assertAll(
                () -> assertEquals(
                        expectedUtcDateTime,
                        actual.getUtcDateTime(),
                        "Unexpected UTC date-time"
                ),
                () -> assertEquals(
                        expectedLocalDateTime,
                        actual.getLocalDateTime(),
                        "Unexpected local date-time"
                )
        );
    }
}
