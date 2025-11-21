package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Embeddable;
import org.jetbrains.annotations.NotNull;

import java.time.ZoneId;
import java.time.ZonedDateTime;

/**
 * Stores zoned date-time as `utc` date-time ONLY, `systemTimezone` will be read from the system
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

    public @NotNull ZoneId getSystemTimezone()
    {
        return toZonedDateTime().getZone();
    }

    public @NotNull ZonedDateTime toZonedDateTime()
    {
        return ZonedDateTimePersistence.computeZonedDateTime(getUtcDateTime());
    }
}
