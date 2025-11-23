package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Column;
import jakarta.persistence.Embeddable;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

/**
 * Stores zoned date-time as `utc` date-time with `local` date-time
 */
@Embeddable
public final class UtcWithLocal extends Utc<UtcWithLocal>
{
    @Column(nullable = true)
    private @Nullable LocalDateTime local;

    private UtcWithLocal(@NotNull ZonedDateTime zonedDateTime)
    {
        super(zonedDateTime);
        local = zonedDateTime.toLocalDateTime();
    }

    private UtcWithLocal()
    {
        super();
    }

    public static @NotNull UtcWithLocal of(@NotNull ZonedDateTime zonedDateTime)
    {
        return new UtcWithLocal(zonedDateTime);
    }

    public static @Nullable UtcWithLocal fromStored(
            @Nullable CharSequence utcDateTime,
            @Nullable CharSequence localDateTime,
            @NotNull String dateTimeFormat
    ) {
        return fromStored(
                utcDateTime != null ? DateTimeUtils.parseAsLocalDateTime(utcDateTime, dateTimeFormat) : null,
                localDateTime != null ? DateTimeUtils.parseAsLocalDateTime(localDateTime, dateTimeFormat) : null
        );
    }

    public static @Nullable UtcWithLocal fromStored(
            @Nullable LocalDateTime utcDateTime,
            @Nullable LocalDateTime localDateTime
    ) {
        ZonedDateTime zonedDateTime = ZonedDateTimePersistence.computeZonedDateTime(
                utcDateTime,
                localDateTime
        );

        return zonedDateTime != null ? new UtcWithLocal(zonedDateTime) : null;
    }

    public @NotNull LocalDateTime getLocalDateTime()
    {
        if (local == null) {
            thisInstanceShouldBeNull();
        }
        return local;
    }

    public @NotNull String getLocalDateTime(@NotNull String format)
    {
        return DateTimeFormatter.ofPattern(format).format(getLocalDateTime());
    }

    public @NotNull ZonedDateTime toZonedDateTime()
    {
        return ZonedDateTimePersistence.computeZonedDateTime(
                getUtcDateTime(),
                getLocalDateTime()
        );
    }
}
