package some;

import io.github.petrknap.persistence.zoneddatetime.LocalDateTimeWithUtcCompanion;
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
    private LocalDateTimeWithUtcCompanion createdAt;
    @Column
    private String content;

    public Note(ZonedDateTime createdAt, String content) {
        this.createdAt = new LocalDateTimeWithUtcCompanion(createdAt);
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
