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

    @Test void attribute_converter()
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
                                " AND note.createdAtUtc = :utc" +
                                " AND note.createdAtUtc = :zoned" +
                                " AND note.updatedAtUtc IS NULL",
                        some.Note.class
                )
                .setParameter("utc", utcDateTime)
                .setParameter("zoned", zonedDateTime)
                .getSingleResult();

        assertAll(
                () -> assertEquals(
                        utcDateTime,
                        createdNote.createdAtUtc,
                        "Unexpected createdNote.createdAtUtc"
                ),
                () -> assertNull(
                        createdNote.updatedAtUtc,
                        "Unexpected createdNote.updatedAtUtc"
                ),
                () -> assertEquals(
                        utcDateTime,
                        loadedNote.createdAtUtc,
                        "Unexpected loadedNote.createdAtUtc"
                ),
                () -> assertNull(
                        loadedNote.updatedAtUtc,
                        "Unexpected loadedNote.updatedAtUtc"
                )
        );
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
