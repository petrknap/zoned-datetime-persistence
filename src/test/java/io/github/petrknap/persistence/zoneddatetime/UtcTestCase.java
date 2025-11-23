package io.github.petrknap.persistence.zoneddatetime;

import org.jetbrains.annotations.NotNull;
import org.junit.jupiter.api.Test;

import java.time.ZonedDateTime;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertSame;

abstract class UtcTestCase extends TestCase
{
    abstract @Test void constructs_itself();

    abstract @Test void now();

    /**
     * @implNote use arguments typed AS IS in embeddable
     */
    abstract @Test void fromStored_objects();

    abstract @Test void fromStored_objects_of_null();

    /**
     * @implNote use arguments typed as scalars
     */
    abstract @Test void fromStored_scalars();

    abstract @Test void fromStored_scalars_of_null();

    @Test void asNullable_returns_this()
    {
        Utc instance = getInstance(zonedDateTime);

        assertSame(instance, instance.asNullable());
    }

    @Test void getUtcDateTime_as_formatted_string()
    {
        assertEquals(
                localDateTimeFormatter.format(utcDateTime),
                getInstance(zonedDateTime).getUtcDateTime(LOCAL_DATETIME_FORMAT)
        );
    }

    @Test void toZonedDateTime()
    {
        assertEquals(
                zonedDateTime,
                getInstance(zonedDateTime).toZonedDateTime()
        );
    }

    abstract protected @NotNull Utc getInstance(@NotNull ZonedDateTime zonedDateTime);
}
