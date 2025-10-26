package io.github.petrknap.persistence.zoneddatetime;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;

public class LocalDateTimeWithUtcCompanion {
    public final LocalDateTime localDateTime;
    public final LocalDateTime utcCompanion;

    public LocalDateTimeWithUtcCompanion(LocalDateTime localDateTime, LocalDateTime utcCompanion) {
        this.localDateTime = localDateTime;
        this.utcCompanion = utcCompanion;
    }

    public LocalDateTimeWithUtcCompanion(ZonedDateTime zonedDateTime) {
        this(zonedDateTime.toLocalDateTime(), ZonedDateTimePersistence.computeUtcCompanion(zonedDateTime));
    }

    public ZonedDateTime toZonedDateTime() {
        return ZonedDateTimePersistence.computeZonedDateTime(localDateTime, utcCompanion);
    }
}
