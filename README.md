# Timezone aware date-time persistence

Most SQL databases (like MySQL) do not natively support storing time zone information alongside `DATETIME` / `TIMESTAMP` values.
This leads to ambiguity when saving date-time, especially in applications running in multiple timezones.

This package solves that by providing tools to work with date-time as a couple of:
- the date-time value in timezone of source data, and
- the companion value which holds the timezone information on the other side.



## Local date-time with UTC companion

The **most useful** thing is to save local date-time with its UTC companion.
This makes it possible to **work with date-time directly in SQL**.
The original date-time is perfect for grouping and filtering, while UTC date-time is needed for correct sorting.

### How to use it

There is support for
**Jakarta Persistence API** ([see `Note.java`](./src/test/java/some/Note.java)),
**Doctrine ORM** ([see `Note.php`](./src/test/php/Some/Note.php)),
and, of course, you can use it manually.

```php
namespace PetrKnap\ZonedDateTimePersistence;

$em = DoctrineTest::prepareEntityManager();

# ORM insert
$em->persist(new Some\Note(
    createdAt: new \DateTime('2025-10-30 23:52'),
    content: 'Doctrine is supported',
));
$em->flush();

# manual insert with static call
$now = new \DateTime('2025-10-26 02:45', new \DateTimeZone('CEST'));
$em->getConnection()->insert('notes', [
    'created_at__local' => $now->format('Y-m-d H:i:s'),
    'created_at__utc' => ZonedDateTimePersistence::computeUtcCompanion($now)->format('Y-m-d H:i:s'),
    'content' => 'We still have summer time',
]);

# manual insert with object instance
$now = new LocalDateTimeWithUtcCompanion(new \DateTime('2025-10-26 02:15', new \DateTimeZone('CET')));
$em->getConnection()->insert('notes', [
    'created_at__local' => $now->getLocalDateTime('Y-m-d H:i:s'),
    'created_at__utc' => $now->getUtcCompanion('Y-m-d H:i:s'),
    'content' => 'Now we have winter time',
]);

# ORM select
/** @var Some\Note[] $notes */
$notes = $em->createQueryBuilder()
    ->select('note')
    ->from(Some\Note::class, 'note')
    ->where('note.createdAt.local BETWEEN :from AND :to')
    ->orderBy('note.createdAt.utc')
    ->getQuery()
    ->execute(['from' => '2025-10-26 00:00', 'to' => '2025-10-26 23:59']);
foreach($notes as $note) {
    echo $note->getCreatedAt()->format('Y-m-d H:i T') . ': '. $note->getContent() . PHP_EOL;
}
```

---

Add [`io.github.petrknap:zoned-datetime-persistence`](https://jitpack.io/#io.github.petrknap/zoned-datetime-persistence) to your build file.
Run [`composer require petrknap/zoned-datetime-persistence`](https://packagist.org/packages/petrknap/zoned-datetime-persistence) to install it.
You can [support this project via donation](https://petrknap.github.io/donate.html).
The project is licensed under [the terms of the `LGPL-3.0-or-later`](./COPYING.LESSER).
