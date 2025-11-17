package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

public final class ZonedDateTimePersistence
{
    private ZonedDateTimePersistence()
    {
    }

    public static @Nullable LocalDateTime computeUtcDateTime(@Nullable ZonedDateTime zonedDateTime)
    {
        return zonedDateTime != null
                ? zonedDateTime.withZoneSameInstant(ZoneId.of("UTC")).toLocalDateTime()
                : null;
    }

    public static @Nullable ZonedDateTime computeZonedDateTime(
            @Nullable LocalDateTime utcDateTime,
            @Nullable LocalDateTime localDateTime
    ) {
        return utcDateTime != null && localDateTime != null ? DateTimeUtils.asUtcInstantAtOffset(
                utcDateTime,
                DateTimeUtils.secondsBetween(utcDateTime, localDateTime)
        ) : null;
    }

    public static @Nullable ZonedDateTime computeZonedDateTime(
            @Nullable LocalDateTime utcDateTime,
            @Nullable ZoneId timezone
    ) {
        return utcDateTime != null && timezone != null ? DateTimeUtils.asUtcInstantAtOffset(
                utcDateTime,
                0
        ).withZoneSameInstant(timezone) : null;
    }
}
