package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Column;
import jakarta.persistence.Embeddable;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

@Embeddable
public final class UtcWithLocal extends Utc {
    @Column
    private LocalDateTime local;

    public UtcWithLocal(ZonedDateTime zonedDateTime) {
        super(zonedDateTime);
        local = zonedDateTime.toLocalDateTime();
    }

    private UtcWithLocal() {
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
