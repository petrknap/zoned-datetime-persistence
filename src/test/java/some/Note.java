package some;

import io.github.petrknap.persistence.zoneddatetime.UtcWithLocal;
import io.github.petrknap.persistence.zoneddatetime.UtcWithTimezone;
import jakarta.persistence.*;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;

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
     * Example: utc date-time with local date-time
     */
    @Embedded
    @AttributeOverrides({
            @AttributeOverride(name = "utc", column = @Column(name = "created_at__utc")),
            @AttributeOverride(name = "local", column = @Column(name = "created_at__local"))
    })
    private @NotNull UtcWithLocal createdAt;

    /**
     * Example: utc date-time with timezone identifier
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
    private @Nullable UtcWithLocal updatedAt;

    @Column(nullable = false)
    private @NotNull String content;

    public Note(
            @NotNull ZonedDateTime createdAt,
            @NotNull String content
    ) {
        this.createdAt = new UtcWithLocal(createdAt);
        this.createdAt2 = new UtcWithTimezone(createdAt);
        this.content = content;
    }

    private Note() {}

    public @Nullable Long getId() {
        return id;
    }

    public @NotNull ZonedDateTime getCreatedAt() {
        return createdAt.toZonedDateTime();
    }

    public @Nullable ZonedDateTime getUpdatedAt() {
        return updatedAt != null ? updatedAt.toZonedDateTime() : null;
    }

    public @NotNull String getContent() {
        return content;
    }

    public void setContent(@NotNull String content) {
        this.content = content;
        updatedAt = new UtcWithLocal(ZonedDateTime.now());
    }
}
