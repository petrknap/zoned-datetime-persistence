package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.AttributeConverter;
import jakarta.persistence.Converter;
import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;

@Converter(autoApply = false)
public final class ZonedDateTimeConverter implements AttributeConverter<ZonedDateTime, LocalDateTime> {
    @Override public @Nullable LocalDateTime convertToDatabaseColumn(@Nullable ZonedDateTime zonedDateTime) {
        return ZonedDateTimePersistence.computeUtcDateTime(zonedDateTime);
    }

    @Override public @Nullable ZonedDateTime convertToEntityAttribute(@Nullable LocalDateTime localDateTime) {
        return ZonedDateTimePersistence.computeZonedDateTime(localDateTime);
    }
}
