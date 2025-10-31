package io.github.petrknap.persistence.zoneddatetime;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

public final class LocalDateTimeWithUtcCompanion {
    private final LocalDateTime local;
    private final LocalDateTime utc;

    public LocalDateTimeWithUtcCompanion(LocalDateTime localDateTime, LocalDateTime utcCompanion) {
        this.local = localDateTime;
        this.utc = utcCompanion;
    }

    public LocalDateTimeWithUtcCompanion(ZonedDateTime zonedDateTime) {
        this(zonedDateTime.toLocalDateTime(), ZonedDateTimePersistence.computeUtcCompanion(zonedDateTime));
    }

    public LocalDateTime getLocalDateTime() {
        return local;
    }

    public String getLocalDateTime(String format) {
        return DateTimeFormatter.ofPattern(format).format(local);
    }

    public LocalDateTime getUtcCompanion() {
        return utc;
    }

    public String getUtcCompanion(String format) {
        return DateTimeFormatter.ofPattern(format).format(utc);
    }

    public ZonedDateTime toZonedDateTime() {
        return ZonedDateTimePersistence.computeZonedDateTime(local, utc);
    }
}
