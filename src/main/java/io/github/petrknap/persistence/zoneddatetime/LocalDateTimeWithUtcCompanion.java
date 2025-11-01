package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Column;
import jakarta.persistence.Embeddable;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

@Embeddable
public final class LocalDateTimeWithUtcCompanion {
    @Column
    private LocalDateTime local;
    @Column
    private LocalDateTime utc;

    public LocalDateTimeWithUtcCompanion(LocalDateTime localDateTime, LocalDateTime utcCompanion) {
        this.local = localDateTime;
        this.utc = utcCompanion;
    }

    public LocalDateTimeWithUtcCompanion(ZonedDateTime zonedDateTime) {
        this(zonedDateTime.toLocalDateTime(), ZonedDateTimePersistence.computeUtcCompanion(zonedDateTime));
    }

    private LocalDateTimeWithUtcCompanion() {}

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
