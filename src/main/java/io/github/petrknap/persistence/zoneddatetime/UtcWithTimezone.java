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

    public static @Nullable UtcWithTimezone ofValues(
            @Nullable CharSequence utcDateTime,
            @Nullable String timezone,
            @NotNull String dateTimeFormat
            ) {
        return ofValues(
                utcDateTime != null ? DateTimeUtils.parseAsLocalDateTime(utcDateTime, dateTimeFormat) : null,
                timezone
        );
    }

    public static @Nullable UtcWithTimezone ofValues(
            @Nullable LocalDateTime utcDateTime,
            @Nullable String timezone
    ) {
        ZonedDateTime zonedDateTime = ZonedDateTimePersistence.computeZonedDateTime(
                utcDateTime,
                timezone != null ? ZoneId.of(timezone) : null
        );

        return zonedDateTime != null ? new UtcWithTimezone(zonedDateTime) : null;
    }

    public UtcWithTimezone(@NotNull ZonedDateTime zonedDateTime)
    {
        super(zonedDateTime);
        timezone = zonedDateTime.getZone().getId();
    }

    private UtcWithTimezone()
    {
        super();
    }

    public @NotNull ZoneId getTimezone()
    {
        if (timezone == null) {
            thisInstanceShouldBeNull();
        }
        return ZoneId.of(timezone);
    }

    public @NotNull ZonedDateTime toZonedDateTime()
    {
        return ZonedDateTimePersistence.computeZonedDateTime(
                getUtcDateTime(),
                getTimezone()
        );
    }
}
