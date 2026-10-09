<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Partial function application');

function greet(string $greeting, string $name, string $punctuation = '!'): string
{
    return "{$greeting}, {$name}{$punctuation}";
}

section('? leaves one argument open and returns a Closure');
$slug = str_replace(' ', '-', ?);
dump($slug('modern php features'));
dump(get_debug_type($slug));

section('Fill some arguments now and the rest later');
$hi = greet('Hi', ?);
dump($hi('Ann'));

section('Works with named arguments');
$toBob = greet(name: 'Bob', greeting: ?);
dump($toBob('Hey'));

section('... leaves all remaining arguments open');
$pair = sprintf('%s and %s', ...);
dump($pair('PHP', 'Laravel'));

section('Before PHP 8.6: an arrow function');
$oldSlug = fn(string $text) => str_replace(' ', '-', $text);
dump($oldSlug('modern php features'));

section('A great match for the pipe operator');
$result = '  Modern PHP Features  '
    |> trim(...)
    |> strtolower(...)
    |> str_replace(' ', '-', ?);
dump($result);

section('Each ? is a required parameter of the closure');
try {
    $hi();
} catch (ArgumentCountError $error) {
    dump($error::class);
}
