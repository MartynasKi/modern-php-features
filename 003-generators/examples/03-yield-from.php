<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Delegate with yield from');

function letters(): Generator
{
    yield 'a';
    yield 'b';
}

function numbers(): Generator
{
    yield 1;
    yield 2;
}

function everything(): Generator
{
    yield from letters();
    yield from numbers();
    yield from ['x', 'y'];
}

section('Values from two generators and an array');
foreach (everything() as $key => $value) {
    text("{$key} => {$value}");
}

section('Keys repeat, so iterator_to_array() overwrites values');
dump(iterator_to_array(everything()));

section('Ignore the keys to keep every value');
dump(iterator_to_array(everything(), preserve_keys: false));
