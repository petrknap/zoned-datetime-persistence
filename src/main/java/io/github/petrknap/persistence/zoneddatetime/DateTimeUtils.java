package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;

import java.time.Duration;
import java.time.LocalDateTime;
import java.time.ZoneOffset;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;
import java.time.temporal.Temporal;

final class DateTimeUtils {
    private DateTimeUtils() {
    }

    /**
     * @param offset seconds
     */
    public static @NotNull ZonedDateTime asUtcInstantAtOffset(
            @NotNull LocalDateTime localDateTime,
            int offset
    ) {
        return localDateTime
                .toInstant(ZoneOffset.UTC)
                .atOffset(ZoneOffset.ofTotalSeconds(offset))
                .toZonedDateTime();
    }

    public static @NotNull LocalDateTime parseAsLocalDateTime(
            @NotNull CharSequence datetime,
            @NotNull String format
    ) throws Exception.CouldNotParseAsLocalDateTime {
        try {
            return LocalDateTime.parse(datetime, DateTimeFormatter.ofPattern(format));
        } catch (Throwable cause) {
            throw new Exception.CouldNotParseAsLocalDateTime(datetime, format, cause);
        }
    }

    public static int secondsBetween(
            @NotNull Temporal startInclusive,
            @NotNull Temporal endExclusive
    ) {
        return (int) Duration.between(startInclusive, endExclusive).getSeconds();
    }

    interface Exception {
        final class CouldNotParseAsLocalDateTime extends RuntimeException implements Exception {
            private final transient CharSequence datetime;
            private final String format;

            public CouldNotParseAsLocalDateTime(
                    @NotNull CharSequence datetime,
                    @NotNull String format,
                    @NotNull Throwable cause
            ) {
                super(String.format(
                        "Could not parse the given datetime of %s(%d) as %s using format `%s`",
                        datetime.getClass().getName(),
                        datetime.length(),
                        LocalDateTime.class.getName(),
                        format
                ), cause);
                this.datetime = datetime;
                this.format = format;
            }

            public @NotNull CharSequence getDatetime() {
                return datetime;
            }

            public @NotNull String getFormat() {
                return format;
            }
        }
    }
}
