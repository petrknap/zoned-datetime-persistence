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

    @Override @Test void fromStored_objects()
    {
        assertInstance(
                UtcWithLocal.fromStored(
                        utcDateTime.toLocalDateTime(),
                        localDateTime
                ),
                utcDateTime.toLocalDateTime(),
                localDateTime
        );
    }

    @Override @Test void fromStored_objects_of_null()
    {
        assertNull(UtcWithLocal.fromStored(null, null));
    }

    @Override @Test void fromStored_scalars()
    {
        assertInstance(
                UtcWithLocal.fromStored(
                        localDateTimeFormatter.format(utcDateTime),
                        LOCAL_DATETIME,
                        LOCAL_DATETIME_FORMAT
                ),
                utcDateTime.toLocalDateTime(),
                localDateTime
        );
    }

    @Override @Test void fromStored_scalars_of_null()
    {
        assertNull(UtcWithLocal.fromStored(null, null, LOCAL_DATETIME_FORMAT));
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
        return UtcWithLocal.of(zonedDateTime);
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
