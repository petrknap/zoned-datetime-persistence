package io.github.petrknap.persistence.zoneddatetime;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

public class ZonedDateTimePersistence {
    private ZonedDateTimePersistence() {
    }

    public static LocalDateTime computeUtcCompanion(ZonedDateTime zonedDateTime) {
        return zonedDateTime.withZoneSameInstant(ZoneId.of("UTC")).toLocalDateTime();
    }

    public static ZonedDateTime computeZonedDateTime(LocalDateTime localDateTime, LocalDateTime utcCompanion) {
        return DateTimeUtils.asUtcInstantAtOffset(
                utcCompanion,
                DateTimeUtils.secondsBetween(utcCompanion, localDateTime)
        );
    }

    public static ZonedDateTime computeZonedDateTime(CharSequence localDateTime, CharSequence utcCompanion, String pattern) {
        return computeZonedDateTime(
                DateTimeUtils.parseAsLocalDateTime(localDateTime, pattern),
                DateTimeUtils.parseAsLocalDateTime(utcCompanion, pattern)
        );
    }
}
