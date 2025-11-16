package some;

import io.github.petrknap.persistence.zoneddatetime.UtcWithLocal;
import jakarta.persistence.*;

import java.time.ZonedDateTime;

@Entity
@Table(name = "notes")
public class Note {
    @Id
    @Column
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    @Embedded
    @AttributeOverrides({
            @AttributeOverride(name = "utc", column = @Column(name = "created_at__utc")),
            @AttributeOverride(name = "local", column = @Column(name = "created_at__local"))
    })
    private UtcWithLocal createdAt;
    @Column
    private String content;

    public Note(ZonedDateTime createdAt, String content) {
        this.createdAt = new UtcWithLocal(createdAt);
        this.content = content;
    }

    private Note() {}

    public Long getId() {
        return id;
    }

    public ZonedDateTime getCreatedAt() {
        return createdAt.toZonedDateTime();
    }

    public String getContent() {
        return content;
    }
}
