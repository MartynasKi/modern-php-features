<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Time\Duration');

use Time\Duration;

section('Create a duration from any unit');
$meeting = Duration::fromMinutes(90);
dump($meeting);
dump(Duration::compare(Duration::fromIso8601DurationString('PT1H30M'), $meeting));

section('Calculate with it. Every method returns a new Duration');
$longer = $meeting->add(Duration::fromSeconds(30));
dump($longer->seconds);
dump($meeting->multiplyBy(2)->seconds);
dump($meeting->sub(Duration::fromHours(2))->negative);

section('compare() returns -1, 0 or 1');
dump(Duration::compare($meeting, $longer));

section('Pairs well with hrtime()');
$start = hrtime(true);
usleep(1500);
$elapsed = Duration::fromNanoseconds(hrtime(true) - $start);
text("Took {$elapsed->seconds} s and {$elapsed->nanoseconds} ns");
