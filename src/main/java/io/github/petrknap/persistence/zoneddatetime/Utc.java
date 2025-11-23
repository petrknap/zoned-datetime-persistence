package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.Column;
import jakarta.persistence.MappedSuperclass;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;

import java.time.LocalDateTime;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

@MappedSuperclass
abstract class Utc<T extends Utc<T>> {
    @Column(nullable = true)
    private @Nullable LocalDateTime utc;

    protected Utc(@NotNull ZonedDateTime zonedDateTime)
    {
        utc = ZonedDateTimePersistence.computeUtcDateTime(zonedDateTime);
    }

    protected Utc() {}

    public @Nullable T asNullable()
    {
        return utc == null ? null : (T) this;
    }

    public @NotNull LocalDateTime getUtcDateTime()
    {
        if (utc == null) {
            thisInstanceShouldBeNull();
        }
        return utc;
    }

    public @NotNull String getUtcDateTime(@NotNull String format)
    {
        return DateTimeFormatter.ofPattern(format).format(getUtcDateTime());
    }

    public abstract @NotNull ZonedDateTime toZonedDateTime();

    protected void thisInstanceShouldBeNull() throws IllegalStateException
    {
        throw new IllegalStateException(getClass().getName() + " instance was created without data, call asNullable() method or adjust your object–relational mapping");
    }
}
