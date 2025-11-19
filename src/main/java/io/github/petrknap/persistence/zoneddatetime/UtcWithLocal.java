package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Column;
import jakarta.persistence.Embeddable;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

@Embeddable
public final class UtcWithLocal extends Utc<UtcWithLocal> {
    @Column(nullable = true)
    private @Nullable LocalDateTime local;

    public UtcWithLocal(@NotNull ZonedDateTime zonedDateTime) {
        super(zonedDateTime);
        local = zonedDateTime.toLocalDateTime();
    }

    private UtcWithLocal() {
        super();
    }

    public @NotNull LocalDateTime getLocalDateTime() {
        if (local == null) {
            thisInstanceShouldBeNull();
        }
        return local;
    }

    public @NotNull String getLocalDateTime(@NotNull String format) {
        return DateTimeFormatter.ofPattern(format).format(getLocalDateTime());
    }

    public @NotNull ZonedDateTime toZonedDateTime() {
        return ZonedDateTimePersistence.computeZonedDateTime(getUtcDateTime(), getLocalDateTime());
    }
}
