<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('What can go on the right side');

final class Formatter
{
    public function shout(string $text): string
    {
        return strtoupper($text) . '!';
    }

    public function __invoke(string $text): string
    {
        return "[{$text}]";
    }
}

$formatter = new Formatter();

section('Any callable that takes one argument');
dump('hello' |> strtoupper(...));
dump('hello' |> 'ucfirst');
dump('hello' |> $formatter->shout(...));
dump('hello' |> $formatter);

section('Extra arguments need a wrapping arrow function');
dump('a,b,c' |> (fn(string $text) => explode(',', $text)));

section('A function that needs more arguments fails');
try {
    'a,b,c' |> explode(...);
} catch (ArgumentCountError $error) {
    dump($error->getMessage());
}

section('A by-reference parameter fails');
try {
    [3, 1, 2] |> sort(...);
} catch (Error $error) {
    dump($error->getMessage());
}
