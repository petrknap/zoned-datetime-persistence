package some;

import io.github.petrknap.persistence.zoneddatetime.UtcWithLocal;
import io.github.petrknap.persistence.zoneddatetime.UtcWithTimezone;
import io.github.petrknap.persistence.zoneddatetime.UtcDateTimeConverter;
import jakarta.persistence.*;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;

import java.time.ZoneId;
import java.time.ZonedDateTime;

@Entity
@Table(name = "notes")
final public class Note
{
    @Id
    @Column
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private @Nullable Long id;

    /**
     * Example: UTC date-time with local date-time
     */
    @Embedded
    @AttributeOverrides({
            @AttributeOverride(name = "utc", column = @Column(name = "created_at__utc")),
            @AttributeOverride(name = "local", column = @Column(name = "created_at__local"))
    })
    private @NotNull UtcWithLocal createdAt;

    /**
     * Example: UTC date-time with timezone
     */
    @Embedded
    @AttributeOverrides({
            @AttributeOverride(name = "utc", column = @Column(name = "created_at_2__utc")),
            @AttributeOverride(name = "timezone", column = @Column(name = "created_at_2__timezone"))
    })
    private @NotNull UtcWithTimezone createdAt2;

    /**
     * Example: nullable embeddable
     */
    @Embedded
    private @Nullable UtcWithLocal deletedAt;

    /**
     * Example: UTC date-time converter
     */
    @Column(nullable = false)
    @Convert(converter = UtcDateTimeConverter.class)
    public @NotNull ZonedDateTime createdAtUtc;

    /**
     * Example: converted nullable
     */
    @Column
    @Convert(converter = UtcDateTimeConverter.class)
    public @Nullable ZonedDateTime deletedAtUtc;

    @Column(nullable = false)
    private @NotNull String content;

    public Note(
            @NotNull ZonedDateTime createdAt,
            @NotNull String content
    ) {
        this.createdAt = new UtcWithLocal(createdAt);
        this.createdAt2 = new UtcWithTimezone(createdAt);
        this.createdAtUtc = createdAt.withZoneSameInstant(ZoneId.of("UTC"));
        this.content = content;
    }

    private Note() {}

    public @NotNull ZonedDateTime getCreatedAt() {
        return createdAt.toZonedDateTime();
    }

    public @NotNull ZonedDateTime getCreatedAt2() {
        return createdAt2.toZonedDateTime();
    }

    public @Nullable ZonedDateTime getDeletedAt() {
        return deletedAt != null ? deletedAt.toZonedDateTime() : null;
    }
}
