package io.github.petrknap.persistence.zoneddatetime;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

public final class ZonedDateTimePersistence {
    private ZonedDateTimePersistence() {
    }

    public static LocalDateTime computeUtcDateTime(ZonedDateTime zonedDateTime) {
        return zonedDateTime.withZoneSameInstant(ZoneId.of("UTC")).toLocalDateTime();
    }

    public static ZonedDateTime computeZonedDateTime(LocalDateTime utcDateTime, LocalDateTime localDateTime) {
        return DateTimeUtils.asUtcInstantAtOffset(
                utcDateTime,
                DateTimeUtils.secondsBetween(utcDateTime, localDateTime)
        );
    }

    public static ZonedDateTime computeZonedDateTime(CharSequence utcDateTime, CharSequence localDateTime, String format) throws Exception.CouldNotComputeZonedDateTime {
        try {
            return computeZonedDateTime(
                    DateTimeUtils.parseAsLocalDateTime(utcDateTime, format),
                    DateTimeUtils.parseAsLocalDateTime(localDateTime, format)
            );
        } catch (DateTimeUtils.Exception.CouldNotParseAsLocalDateTime cause) {
            throw new Exception.CouldNotComputeZonedDateTime(cause);
        }
    }

    interface Exception {
        final class CouldNotComputeZonedDateTime extends RuntimeException implements Exception {
            public CouldNotComputeZonedDateTime(Throwable cause) {
                super(cause.getMessage(), cause);
            }
        }
    }
}
