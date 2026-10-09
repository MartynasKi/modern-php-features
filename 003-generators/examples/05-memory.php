<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Memory: array vs generator');

const ROWS = 1_000_000;

function numbersArray(): array
{
    $numbers = [];

    for ($i = 1; $i <= ROWS; $i++) {
        $numbers[] = $i;
    }

    return $numbers;
}

function numbersGenerator(): Generator
{
    for ($i = 1; $i <= ROWS; $i++) {
        yield $i;
    }
}

function sumWithPeak(callable $numbers): array
{
    memory_reset_peak_usage();
    $before = memory_get_usage();

    $sum = 0;

    foreach ($numbers() as $number) {
        $sum += $number;
    }

    $peak = memory_get_peak_usage() - $before;

    return [
        'sum' => $sum,
        'extra memory' => number_format($peak / 1024, 1) . ' KB',
    ];
}

section('Array with ' . number_format(ROWS) . ' numbers');
dump(sumWithPeak(numbersArray(...)));

section('Generator with ' . number_format(ROWS) . ' numbers');
dump(sumWithPeak(numbersGenerator(...)));
