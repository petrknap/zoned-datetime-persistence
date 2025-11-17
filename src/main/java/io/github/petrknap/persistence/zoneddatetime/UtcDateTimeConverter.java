package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.AttributeConverter;
import jakarta.persistence.Converter;
import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZoneId;
import java.time.ZonedDateTime;

/**
 * Converts zoned date-time into UTC date-time
 */
@Converter(autoApply = false)
public final class UtcDateTimeConverter implements AttributeConverter<ZonedDateTime, LocalDateTime>
{
    @Override public @Nullable LocalDateTime convertToDatabaseColumn(@Nullable ZonedDateTime value)
    {
        return ZonedDateTimePersistence.computeUtcDateTime(value);
    }

    @Override public @Nullable ZonedDateTime convertToEntityAttribute(@Nullable LocalDateTime value)
    {
        return ZonedDateTimePersistence.computeZonedDateTime(
                value,
                ZoneId.of("UTC")
        );
    }
}
