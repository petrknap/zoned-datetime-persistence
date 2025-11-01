package io.github.petrknap.persistence.zoneddatetime;

import jakarta.persistence.*;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.assertEquals;

final class JpaTest extends TestCase {
    public static EntityManager prepareEntityManage() {
        EntityManager entityManager = Persistence
                .createEntityManagerFactory("test")
                .createEntityManager();

        entityManager.getTransaction().begin();

        return entityManager;
    }

    @Test void embeddable() {
        EntityManager entityManager = prepareEntityManage();
        some.Note createdNote = new some.Note(zonedDateTime, "");
        entityManager.persist(createdNote);
        entityManager.flush();
        entityManager.clear();

        some.Note loadedNote = entityManager.find(some.Note.class, createdNote.getId());

        assertEquals(
            zonedDateTime,
            loadedNote.getCreatedAt()
        );
    }
}
