package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.*;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertEquals;

final class JpaTest extends TestCase {
    public static EntityManager prepareEntityManager() {
        EntityManager entityManager = Persistence
                .createEntityManagerFactory("test")
                .createEntityManager();

        entityManager.getTransaction().begin();

        return entityManager;
    }

    @Test void embeddable() {
        EntityManager entityManager = prepareEntityManager();
        some.Note createdNote = new some.Note(zonedDateTime, "");
        entityManager.persist(createdNote);
        entityManager.flush();
        entityManager.clear();

        some.Note loadedNote = entityManager
                .createQuery(
                        "SELECT note FROM " + some.Note.class.getName() + " note" +
                                " WHERE note.createdAt.local = :local AND note.createdAt.utc = :utc",
                        some.Note.class
                )
                .setParameter("local", localDateTime)
                .setParameter("utc", utcDateTime.toLocalDateTime())
                .getSingleResult();

        assertEquals(
            zonedDateTime,
            loadedNote.getCreatedAt()
        );
    }
}
