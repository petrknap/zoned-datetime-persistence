package io.github.petrknap.persistence.zoneddatetime;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

public final class ZonedDateTimePersistence {
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

    public static ZonedDateTime computeZonedDateTime(CharSequence localDateTime, CharSequence utcCompanion, String pattern) throws Exception.CouldNotComputeZonedDateTime {
        try {
            return computeZonedDateTime(
                    DateTimeUtils.parseAsLocalDateTime(localDateTime, pattern),
                    DateTimeUtils.parseAsLocalDateTime(utcCompanion, pattern)
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
