# Timezone aware date-time persistence

[![GitHub](https://img.shields.io/github/v/release/petrknap/zoned-datetime-persistence?include_prereleases&label=GitHub&style=flat)](https://github.com/petrknap/zoned-datetime-persistence/releases)
[![JitPack](https://img.shields.io/jitpack/version/io.github.petrknap/zoned-datetime-persistence?label=JitPack&style=flat)](https://jitpack.io/#io.github.petrknap/zoned-datetime-persistence)
[![Packagist](https://img.shields.io/packagist/v/petrknap/zoned-datetime-persistence?label=Packagist&style=flat)](https://packagist.org/packages/petrknap/zoned-datetime-persistence)

Many data storage systems (like MySQL) do not natively support storing timezone information alongside date-time values.
This limitation introduces ambiguity when handling zoned date-times — particularly in applications operating across multiple timezones or even within a single timezone that observes multiple offsets (e.g. due to daylight saving time).

This package addresses the issue by providing tools that treat zoned date-time as a pair consisting of:
- the UTC date-time value, and
- a companion value that explicitly captures the corresponding timezone information.



## Implemented

- [UTC with local date-time](#utc-with-local-date-time)
  - [How to use it](#how-to-use-it)
- [UTC with timezone](#utc-with-timezone)
  - [UTC with system timezone](#utc-with-system-timezone)
  - [UTC date-time converter / type / cast](#utc-date-time-converter--type--cast)


### UTC with local date-time

> `UtcWithLocal`

The **most useful** approach is to store the **UTC date-time together with its local counterpart**.
This dual representation enables **seamless manipulation** of date-time values directly **within storage system**.
The local date-time is ideal for grouping and filtering based on user or business context, while the UTC value ensures consistent and accurate sorting across timezones.

#### How to use it

There is built-in support for
the **Jakarta Persistence API** (see [`Note.java`](./src/test/java/some/Note.java) and [`JpaTest.java`](./src/test/java/io/github/petrknap/persistence/zoneddatetime/JpaTest.java)),
the **Doctrine ORM** (see [`Note.php`](./src/test/php/Some/Note.php) and [`DoctrineTest.php`](./src/test/php/DoctrineTest.php)),
the **Eloquent** (see [`NoteModel.php`](./src/test/php/Some/NoteModel.php) and [`EloquentTest.php`](./src/test/php/EloquentTest.php)),
and, of course, it **can be integrated manually** into any project, giving you full flexibility to adapt it to your specific needs.

```php
namespace PetrKnap\Persistence\ZonedDateTime;

$em = DoctrineTest::prepareEntityManager();

# persist entity
$em->persist(new Some\Note(
    createdAt: new \DateTimeImmutable('2025-10-30 23:52'),
    content: "It's dark outside...",
));
$em->flush();

# insert data manually (static call)
$now = new \DateTimeImmutable('2025-10-26 02:45', new \DateTimeZone('CEST'));
$em->getConnection()->insert('notes', [
    'created_at__utc' => ZonedDateTimePersistence::computeUtcDateTime($now)->format('Y-m-d H:i:s'),
    'created_at__local' => $now->format('Y-m-d H:i:s'),
    'content' => 'We still have summer time',
]);

# insert data manually (object instance)
$now = new UtcWithLocal(new \DateTimeImmutable('2025-10-26 02:15', new \DateTimeZone('CET')));
$em->getConnection()->insert('notes', [
    'created_at__utc' => $now->getUtcDateTime('Y-m-d H:i:s'),
    'created_at__local' => $now->getLocalDateTime('Y-m-d H:i:s'),
    'content' => 'Now we have winter time',
]);

# select entities
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
```
2025-10-26 02:45 GMT+0200: We still have summer time
2025-10-26 02:15 GMT+0100: Now we have winter time
```


### UTC with timezone

> `UtcWithTimezone`

If you want to **preserve the original timezone as is**, you cannot use [`UtcWithLocal`](#utc-with-local-date-time), because it works over fixed offsets.
In this case, you need to use this implementation.

```php
namespace PetrKnap\Persistence\ZonedDateTime;

$now = (new \DateTime('2025-03-30 01:45', new \DateTimeZone('Europe/Prague')));

echo 'UtcWithTimezone: ' . (new UtcWithTimezone($now))
    ->toZonedDateTime()
    ->modify('+1 hour')
    ->format('Y-m-d H:i T' . PHP_EOL);
echo 'UtcWithLocal:    ' . (new UtcWithLocal($now))
    ->toZonedDateTime()
    ->modify('+1 hour')
    ->format('Y-m-d H:i T' . PHP_EOL);
```
```
UtcWithTimezone: 2025-03-30 03:45 CEST
UtcWithLocal:    2025-03-30 02:45 GMT+0100
```

#### UTC with system timezone

> `UtcWithSystemTimezone`

The **most compact** approach is to store **only the UTC date-time**.
This serves as an alternative to MySQL's `TIMESTAMP`, Postgres's `TIMESTAMP WITH TIMEZONE`, and [custom ORM types](#utc-date-time-converter--type--cast).
It offers full range of `DateTime`, avoids normalization on connection, adds `.utc` into your queries for better readability and didn't need special configuration.

#### UTC date-time converter / type / cast

> `UtcDateTimeConverter` <sup><small>Jakarta Persistence API</small></sup>

This converter transparently manages conversions of `ZonedDateTime`, including JPQL parameters.
That means you **no longer need to worry** about manual timezone adjustments.

For examples, see [the attributes `Note.createdAtUtc` and `Note.deletedAtUtc`](./src/test/java/some/Note.java) and [the `JpaTest`](./src/test/java/io/github/petrknap/persistence/zoneddatetime/JpaTest.java).

> `UtcDateTimeType` <sup><small>Doctrine ORM</small></sup>

In contrast to `UtcDateTimeConverter`, this type does **not** automatically adjust the timezone of DQL parameters.
You must therefore **provide the type when you are calling `setParameter`** on your queries.
Also, you have to **register the type** in your Doctrine configuration manually.

For examples, see [the attributes `Note.createdAtUtc` and `Note.deletedAtUtc`](./src/test/php/Some/Note.php) and [the `DoctrineTest`](./src/test/php/DoctrineTest.php).

> `AsUtcDateTime` <sup><small>Eloquent</small></sup>

In contrast to `UtcDateTimeConverter` and `UtcDateTimeType`, this cast may or may **not** adjust the timezone of any input.
You should therefore **handle timezone conversions explicitly everytime you are providing date-time into Eloquent**.
But the conversion after hydration works well.

For examples, see [the attributes `NoteModel.created_at_utc` and `NoteModel.deleted_at_utc`](./src/test/php/Some/NoteModel.php) and [the `EloquentTest`](./src/test/php/EloquentTest.php).


---

You can [support this project via donation](https://petrknap.github.io/donate.html).
The project is licensed under [the terms of the `LGPL-3.0-or-later`](./COPYING.LESSER).
