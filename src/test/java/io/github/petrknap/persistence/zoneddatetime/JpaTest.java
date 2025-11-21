package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.*;
import org.jetbrains.annotations.NotNull;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertAll;
import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNull;

final class JpaTest extends TestCase {
    static @NotNull EntityManager prepareEntityManager() {
        EntityManager entityManager = Persistence
                .createEntityManagerFactory("test")
                .createEntityManager();

        entityManager.getTransaction().begin();

        return entityManager;
    }

    @Test void converter() {
        EntityManager entityManager = prepareEntityManager();
        some.Note createdNote = new some.Note(zonedDateTime, "test");
        entityManager.persist(createdNote);
        entityManager.flush();
        entityManager.clear();
        some.Note loadedNote = entityManager
                .createQuery(
                        "SELECT note FROM " + some.Note.class.getName() + " note" +
                                " WHERE note.content = 'test'" +
                                " AND note.zonedCreatedAt = :utc" +
                                " AND note.zonedCreatedAt = :zoned" +
                                " AND note.zonedUpdatedAt IS NULL",
                        some.Note.class
                )
                .setParameter("utc", utcDateTime)
                .setParameter("zoned", zonedDateTime)
                .getSingleResult();

        assertAll(
                () -> assertEquals(
                        zonedDateTime,
                        createdNote.zonedCreatedAt,
                        "Incorrect createdNote.zonedCreatedAt()"
                ),
                () -> assertNull(
                        createdNote.zonedUpdatedAt,
                        "Incorrect createdNote.zonedUpdatedAt()"
                ),
                () -> assertEquals(
                        zonedDateTime,
                        loadedNote.zonedCreatedAt,
                        "Incorrect loadedNote.zonedCreatedAt()"
                ),
                () -> assertNull(
                        loadedNote.zonedUpdatedAt,
                        "Incorrect loadedNote.zonedUpdatedAt()"
                )
        );
    }

    @Test void embedabbles() {
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
                                " AND note.createdAt2.utc = :utc" +
                                " AND note.updatedAt.utc IS NULL",
                        some.Note.class
                )
                .setParameter("utc", utcDateTime.toLocalDateTime())
                .setParameter("local", localDateTime)
                .getSingleResult();

        assertAll(
                () -> assertEquals(
                        zonedDateTime,
                        createdNote.getCreatedAt(),
                        "Incorrect createdNote.getCreatedAt()"
                ),
                () -> assertNull(
                        createdNote.getUpdatedAt(),
                        "Incorrect createdNote.getUpdatedAt()"
                ),
                () -> assertEquals(
                        zonedDateTime,
                        loadedNote.getCreatedAt(),
                        "Incorrect loadedNote.getCreatedAt()"
                ),
                () -> assertNull(
                        loadedNote.getUpdatedAt(),
                        "Incorrect loadedNote.getUpdatedAt()"
                )
        );
    }
}
