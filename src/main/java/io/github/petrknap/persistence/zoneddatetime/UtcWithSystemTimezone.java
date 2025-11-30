package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Embeddable;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

/**
 * Stores zoned date-time as `utc` date-time ONLY, system timezone will be read from the system
 */
@Embeddable
public final class UtcWithSystemTimezone extends Utc<UtcWithSystemTimezone>
{
    public UtcWithSystemTimezone(@NotNull ZonedDateTime zonedDateTime)
    {
        super(zonedDateTime);
    }

    private UtcWithSystemTimezone()
    {
        super();
    }

    public static @Nullable UtcWithSystemTimezone fromFormattedValue(
            @Nullable CharSequence utcDateTime,
            @NotNull String dateTimeFormat
    ) {
        return fromValue(
                utcDateTime != null ? DateTimeUtils.parseAsLocalDateTime(utcDateTime, dateTimeFormat) : null
        );
    }

    public static @Nullable UtcWithSystemTimezone fromValue(
            @Nullable LocalDateTime utcDateTime
    ) {
        ZonedDateTime zonedDateTime = ZonedDateTimePersistence.computeZonedDateTime(
                utcDateTime,
                systemTimezone()
        );

        return zonedDateTime != null ? new UtcWithSystemTimezone(zonedDateTime) : null;
    }

    public @NotNull ZonedDateTime toZonedDateTime()
    {
        return ZonedDateTimePersistence.computeZonedDateTime(
                getUtcDateTime(),
                systemTimezone()
        );
    }

    private static @NotNull ZoneId systemTimezone()
    {
        return ZoneId.systemDefault().normalized();
    }
}
