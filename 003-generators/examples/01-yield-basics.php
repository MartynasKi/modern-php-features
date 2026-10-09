<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Yield values and keys');

function countdown(int $from): Generator
{
    text('Generator body starts');

    for ($i = $from; $i > 0; $i--) {
        yield $i;
    }

    text('Generator body ends');
}

section('Calling countdown() runs nothing yet');
$generator = countdown(3);
dump(get_debug_type($generator));

section('foreach pulls one value at a time');
foreach ($generator as $key => $value) {
    text("{$key} => {$value}");
}

function prices(): Generator
{
    yield 'apple' => 1.2;
    yield 'bread' => 2.5;
    yield 'apple' => 0.9;
}

section('Custom keys can repeat');
foreach (prices() as $item => $price) {
    text("{$item} => {$price}");
}

section('iterator_to_array() keeps only the last apple');
dump(iterator_to_array(prices()));
