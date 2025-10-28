package io.github.petrknap.persistence.zoneddatetime;

import org.junit.jupiter.api.DisplayNameGeneration;
import org.junit.jupiter.api.DisplayNameGenerator;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

@DisplayNameGeneration(DisplayNameGenerator.ReplaceUnderscores.class)
abstract class TestCase {
    protected final static String LOCAL_DATETIME = "2025-10-25 16:05";
    protected final static String LOCAL_DATETIME_PATTERN = "yyyy-MM-dd HH:mm";
    protected final static String ZONED_DATETIME = "2025-10-25 16:05 +02:00";
    protected final static int ZONED_DATETIME_OFFSET = 7200;
    protected final static String ZONED_DATETIME_PATTERN = "yyyy-MM-dd HH:mm XXX";

    protected final DateTimeFormatter localDateTimeFormatter = DateTimeFormatter.ofPattern(LOCAL_DATETIME_PATTERN);
    protected final LocalDateTime localDateTime = LocalDateTime.parse(LOCAL_DATETIME, localDateTimeFormatter);
    protected final DateTimeFormatter zonedDateTimeFormatter = DateTimeFormatter.ofPattern(ZONED_DATETIME_PATTERN);
    protected final ZonedDateTime zonedDateTime = ZonedDateTime.parse(ZONED_DATETIME, zonedDateTimeFormatter);
    protected final ZonedDateTime utcDateTime = zonedDateTime.withZoneSameInstant(ZoneId.of("UTC"));
}
