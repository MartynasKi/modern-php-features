<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('JIT: a win and a non-win');

function math(): int
{
    $sum = 0;

    for ($i = 0; $i < 20_000_000; $i++) {
        $sum += $i % 7 * 3;
    }

    return $sum;
}

function strings(): int
{
    $total = 0;

    for ($i = 0; $i < 300_000; $i++) {
        $total += strlen(md5(str_repeat('php', 20)));
    }

    return $total;
}

function milliseconds(callable $callback): int
{
    $start = hrtime(true);
    $callback();

    return intdiv(hrtime(true) - $start, 1_000_000);
}

$status = opcache_get_status(false);

dump([
    'JIT on' => $status['jit']['on'] ?? false,
    'math (ms)' => milliseconds(math(...)),
    'strings (ms)' => milliseconds(strings(...)),
]);
