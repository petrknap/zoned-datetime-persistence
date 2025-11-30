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

    @Test void loads_persisted_entity()
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
                                // -------------------------------------------------------------------------------------
                                // Case: UTC date-time with local date-time
                                " AND note.createdAt.utc = :localUtc AND note.createdAt.local = :local" +
                                // -------------------------------------------------------------------------------------
                                // Case: UTC date-time with timezone
                                " AND note.createdAt2.utc = :localUtc AND note.createdAt2.timezone = :timezone" +
                                // -------------------------------------------------------------------------------------
                                // Case: nullable embeddable
                                " AND note.deletedAt.utc IS NULL" +
                                // -------------------------------------------------------------------------------------
                                // Case: UTC date-time converter
                                " AND note.createdAtUtc = :zonedUtc" +
                                " AND note.createdAtUtc = :zoned" +
                                // -------------------------------------------------------------------------------------
                                // Case: converted nullable
                                " AND note.deletedAtUtc IS NULL" +
                                // -------------------------------------------------------------------------------------
                                " ",
                        some.Note.class
                )
                .setParameter("localUtc", utcDateTime.toLocalDateTime())
                .setParameter("local", localDateTime)
                .setParameter("timezone", zonedDateTime.getZone().getId())
                .setParameter("zonedUtc", utcDateTime)
                .setParameter("zoned", zonedDateTime)
                .getSingleResult();

        assertAll(
                // -----------------------------------------------------------------------------------------------------
                // Case: UTC date-time with local date-time
                () -> assertEquals(
                        zonedDateTime,
                        createdNote.getCreatedAt(),
                        "Unexpected createdNote.getCreatedAt()"
                ),
                () -> assertEquals(
                        zonedDateTime,
                        loadedNote.getCreatedAt(),
                        "Unexpected loadedNote.getCreatedAt()"
                ),
                // -----------------------------------------------------------------------------------------------------
                // Case: UTC date-time with timezone
                () -> assertEquals(
                        zonedDateTime,
                        createdNote.getCreatedAt2(),
                        "Unexpected createdNote.getCreatedAt2()"
                ),
                () -> assertEquals(
                        zonedDateTime,
                        loadedNote.getCreatedAt2(),
                        "Unexpected loadedNote.getCreatedAt2()"
                ),
                // -----------------------------------------------------------------------------------------------------
                // Case: nullable embeddable
                () -> assertNull(
                        createdNote.getDeletedAt(),
                        "Unexpected createdNote.getDeletedAt()"
                ),
                () -> assertNull(
                        loadedNote.getDeletedAt(),
                        "Unexpected loadedNote.getDeletedAt()"
                ),
                // -----------------------------------------------------------------------------------------------------
                // Case: UTC date-time converter
                () -> assertEquals(
                        utcDateTime,
                        createdNote.createdAtUtc,
                        "Unexpected createdNote.createdAtUtc"
                ),
                () -> assertEquals(
                        utcDateTime,
                        loadedNote.createdAtUtc,
                        "Unexpected loadedNote.createdAtUtc"
                ),
                // -----------------------------------------------------------------------------------------------------
                // Case: converted nullable
                () -> assertNull(
                        createdNote.deletedAtUtc,
                        "Unexpected createdNote.deletedAtUtc"
                ),
                () -> assertNull(
                        loadedNote.deletedAtUtc,
                        "Unexpected loadedNote.deletedAtUtc"
                ),
                // -----------------------------------------------------------------------------------------------------
                () -> {}
        );
    }
}
