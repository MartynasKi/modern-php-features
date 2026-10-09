<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('hrtime() instead of microtime()');

function work(): void
{
    $sum = 0;

    for ($i = 0; $i < 100_000; $i++) {
        $sum += $i;
    }
}

section('microtime(true) gives wall clock seconds as a float');
$start = microtime(true);
work();
dump(microtime(true) - $start);

section('hrtime(true) gives nanoseconds as an int');
$start = hrtime(true);
work();
$nanoseconds = hrtime(true) - $start;
dump($nanoseconds);
dump(number_format($nanoseconds / 1e6, 3) . ' ms');

section('The smallest step a microtime() float can show today');
$now = microtime(true);
$step = 2 ** (floor(log($now, 2)) - 52);
dump(number_format($step * 1e9) . ' ns');

section('hrtime() without true returns seconds and nanoseconds');
dump(hrtime());
