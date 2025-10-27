package io.github.petrknap.persistence.zoneddatetime;

import java.time.Duration;
import java.time.LocalDateTime;
import java.time.ZoneOffset;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;
import java.time.temporal.Temporal;

class DateTimeUtils {
    private DateTimeUtils() {
    }

    /**
     * @param offset seconds
     */
    public static ZonedDateTime asUtcInstantAtOffset(LocalDateTime localDateTime, int offset) {
        return localDateTime
                .toInstant(ZoneOffset.UTC)
                .atOffset(ZoneOffset.ofTotalSeconds(offset))
                .toZonedDateTime();
    }

    public static LocalDateTime parseAsLocalDateTime(CharSequence text, String pattern) throws Exception.CouldNotParseAsLocalDateTime {
        try {
            return LocalDateTime.parse(text, DateTimeFormatter.ofPattern(pattern));
        } catch (DateTimeParseException|IllegalArgumentException cause) {
            throw new Exception.CouldNotParseAsLocalDateTime(text, pattern, cause);
        }
    }

    public static int secondsBetween(Temporal startInclusive, Temporal endExclusive) {
        return (int) Duration.between(startInclusive, endExclusive).getSeconds();
    }

    interface Exception {
        class CouldNotParseAsLocalDateTime extends RuntimeException implements Exception {
            public final String text;
            public final String pattern;

            public CouldNotParseAsLocalDateTime(CharSequence text, String pattern, Throwable cause) {
                super(cause);
                this.text = text.toString();
                this.pattern = pattern;
            }
        }
    }
}
