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

```php
use PetrKnap\ZonedDateTimePersistence\LocalDateTimeWithUtcCompanion;
use PetrKnap\ZonedDateTimePersistence\ZonedDateTimePersistence;

$db = new PDO('sqlite::memory:');
$db->exec('CREATE TABLE notes (created_at DATETIME, created_at__utc DATETIME, content TEXT)');
$dbDateTimeFormat = 'Y-m-d H:i:s';

$insert = $db->prepare('INSERT INTO notes VALUES (?, ?, ?)');
# static call usage
$now = new DateTime('2025-10-26 02:45', new DateTimeZone('CEST'));
$insert->execute([
    $now->format($dbDateTimeFormat),
    ZonedDateTimePersistence::computeUtcCompanion($now)->format($dbDateTimeFormat),
    'We still have summer time',
]);
# record usage
$now = new LocalDateTimeWithUtcCompanion(new DateTime('2025-10-26 02:15', new DateTimeZone('CET')));
$insert->execute([
    $now->localDateTime->format($dbDateTimeFormat),
    $now->utcCompanion->format($dbDateTimeFormat),
    'Now we have winter time',
]);

$select = $db->query('SELECT * FROM notes WHERE strftime("%Y", created_at) = "2025" ORDER BY created_at__utc ASC');
foreach($select->fetchAll(PDO::FETCH_ASSOC) as $note) {
    printf(
        '%s: %s' . PHP_EOL,
        ZonedDateTimePersistence::computeZonedDateTime(
            $note['created_at'],
            $note['created_at__utc'],
            $dbDateTimeFormat,
        )->format('Y-m-d H:i T'),
        $note['content'],
    );
}
```

---

Run `composer require petrknap/zoned-datetime-persistence` to install it.
You can [support this project via donation](https://petrknap.github.io/donate.html).
The project is licensed under [the terms of the `LGPL-3.0-or-later`](./COPYING.LESSER).
