package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Column;
import jakarta.persistence.MappedSuperclass;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

@MappedSuperclass
abstract class UtcDateTimeBase {
    @Column
    private LocalDateTime utc;

    protected UtcDateTimeBase(ZonedDateTime zonedDateTime) {
        utc = ZonedDateTimePersistence.computeUtcDateTime(zonedDateTime);
    }

    protected UtcDateTimeBase() {}

    public LocalDateTime getUtcDateTime() {
        return utc;
    }

    public String getUtcDateTime(String format) {
        return DateTimeFormatter.ofPattern(format).format(utc);
    }

    public abstract ZonedDateTime toZonedDateTime();
}
