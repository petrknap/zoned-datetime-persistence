package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.*;
import org.jetbrains.annotations.NotNull;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertAll;
import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNull;

final class JpaTest extends TestCase
{
    static @NotNull EntityManager prepareEntityManager()
    {
        EntityManager entityManager = Persistence
                .createEntityManagerFactory("test")
                .createEntityManager();

        entityManager.getTransaction().begin();

        return entityManager;
    }

    @Test void embeddables()
    {
        EntityManager entityManager = prepareEntityManager();
        some.Note createdNote = new some.Note(zonedDateTime, "test");
        entityManager.persist(createdNote);
        entityManager.flush();
        entityManager.clear();
        some.Note loadedNote = entityManager
                .createQuery(
                        "SELECT note FROM " + some.Note.class.getName() + " note" +
                                " WHERE note.content = 'test'" +
                                " AND note.createdAt.utc = :utc AND note.createdAt.local = :local" +
                                " AND note.createdAt2.utc = :utc AND note.createdAt2.timezone = :timezone" +
                                " AND note.updatedAt.utc IS NULL",
                        some.Note.class
                )
                .setParameter("utc", utcDateTime.toLocalDateTime())
                .setParameter("local", localDateTime)
                .setParameter("timezone", zonedDateTime.getZone().getId())
                .getSingleResult();

        assertAll(
                () -> assertEquals(
                        zonedDateTime,
                        createdNote.getCreatedAt(),
                        "Unexpected createdNote.getCreatedAt()"
                ),
                () -> assertNull(
                        createdNote.getUpdatedAt(),
                        "Unexpected createdNote.getUpdatedAt()"
                ),
                () -> assertEquals(
                        zonedDateTime,
                        loadedNote.getCreatedAt(),
                        "Unexpected loadedNote.getCreatedAt()"
                ),
                () -> assertNull(
                        loadedNote.getUpdatedAt(),
                        "Unexpected loadedNote.getUpdatedAt()"
                )
        );
    }
}
