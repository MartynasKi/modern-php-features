<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Strict types');

function double(int $number): int
{
    return $number * 2;
}

function half(float $number): float
{
    return $number / 2;
}

section('Works');
dump([
    'double(5)' => double(5),
    'half(5)' => half(5),
]);

section('Throws a TypeError');

try {
    double('5');
} catch (TypeError $error) {
    dump($error->getMessage());
}

try {
    strlen(123);
} catch (TypeError $error) {
    dump($error->getMessage());
}

section('Not affected');
dump([
    "'5' + 1" => '5' + 1,
    "'5' == 5" => '5' == 5,
]);
