<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Strict types');

function double(int $number): int
{
    return $number * 2;
}

section('Passing an int');
dump(double(5));

section('Passing a numeric string');

try {
    double('5');
} catch (TypeError $error) {
    dump($error->getMessage());
}
