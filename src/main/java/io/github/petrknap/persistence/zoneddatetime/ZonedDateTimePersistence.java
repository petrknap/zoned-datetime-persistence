package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

public final class ZonedDateTimePersistence {
    private ZonedDateTimePersistence() {
    }

    public static @Nullable LocalDateTime computeUtcDateTime(@Nullable ZonedDateTime zonedDateTime) {
        return zonedDateTime != null ? zonedDateTime.withZoneSameInstant(ZoneId.of("UTC")).toLocalDateTime() : null;
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
            @Nullable CharSequence utcDateTime,
            @Nullable CharSequence localDateTime,
            @NotNull String format
    ) throws Exception.CouldNotComputeZonedDateTime {
        try {
            return utcDateTime != null && localDateTime != null ? computeZonedDateTime(
                    DateTimeUtils.parseAsLocalDateTime(utcDateTime, format),
                    DateTimeUtils.parseAsLocalDateTime(localDateTime, format)
            ) : null;
        } catch (DateTimeUtils.Exception.CouldNotParseAsLocalDateTime cause) {
            throw new Exception.CouldNotComputeZonedDateTime(cause);
        }
    }

    interface Exception {
        final class CouldNotComputeZonedDateTime extends RuntimeException implements Exception {
            public CouldNotComputeZonedDateTime(@NotNull Throwable cause) {
                super(cause.getMessage(), cause);
            }
        }
    }
}
