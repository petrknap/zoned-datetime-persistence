package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Column;
import jakarta.persistence.Embeddable;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

/**
 * Stores zoned date-time as `utc` date-time with `timezone` identifier
 */
@Embeddable
public final class UtcWithTimezone extends Utc<UtcWithTimezone>
{
    @Column(length = 64, nullable = true)
    private @Nullable String timezone;

    public UtcWithTimezone(@NotNull ZonedDateTime zonedDateTime)
    {
        super(zonedDateTime);
        timezone = zonedDateTime.getZone().getId();
    }

    private UtcWithTimezone()
    {
        super();
    }

    public static @Nullable UtcWithTimezone fromFormattedValues(
            @Nullable CharSequence utcDateTime,
            @NotNull String dateTimeFormat,
            @Nullable String timezone
    ) {
        return fromValues(
                utcDateTime != null ? DateTimeUtils.parseAsLocalDateTime(utcDateTime, dateTimeFormat) : null,
                timezone != null ? ZoneId.of(timezone) : null
        );
    }

    public static @Nullable UtcWithTimezone fromValues(
            @Nullable LocalDateTime utcDateTime,
            @Nullable ZoneId timezone
    ) {
        ZonedDateTime zonedDateTime = ZonedDateTimePersistence.computeZonedDateTime(
                utcDateTime,
                timezone
        );

        return zonedDateTime != null ? new UtcWithTimezone(zonedDateTime) : null;
    }

    public @NotNull ZoneId getTimezone()
    {
        return ZoneId.of(getTimezone(true));
    }

    public @NotNull String getTimezone(boolean formatted)
    {
        if (!formatted) {
            throw new IllegalArgumentException("Argument formatted must be true");
        }
        if (timezone == null) {
            thisInstanceShouldBeNull();
        }
        return timezone;
    }

    public @NotNull ZonedDateTime toZonedDateTime()
    {
        return ZonedDateTimePersistence.computeZonedDateTime(
                getUtcDateTime(),
                getTimezone()
        );
    }
}
