package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Column;
import jakarta.persistence.Embeddable;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

@Embeddable
public final class UtcWithLocalDateTime extends UtcDateTimeBase {
    @Column
    private LocalDateTime local;

    public UtcWithLocalDateTime(ZonedDateTime zonedDateTime) {
        super(zonedDateTime);
        local = zonedDateTime.toLocalDateTime();
    }

    private UtcWithLocalDateTime() {
        super();
    }

    public LocalDateTime getLocalDateTime() {
        return local;
    }

    public String getLocalDateTime(String format) {
        return DateTimeFormatter.ofPattern(format).format(local);
    }

    public ZonedDateTime toZonedDateTime() {
        return ZonedDateTimePersistence.computeZonedDateTime(getUtcDateTime(), local);
    }
}
