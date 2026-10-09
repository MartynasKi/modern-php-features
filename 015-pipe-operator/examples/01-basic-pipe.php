<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('The pipe operator');

$title = '  Modern PHP Features  ';

section('Nested calls read inside out');
dump(str_replace(' ', '-', strtolower(trim($title))));

section('Temporary variables read top to bottom but add noise');
$slug = trim($title);
$slug = strtolower($slug);
$slug = str_replace(' ', '-', $slug);
dump($slug);

section('A pipe reads left to right');
$slug = $title
    |> trim(...)
    |> strtolower(...)
    |> (fn(string $text) => str_replace(' ', '-', $text));
dump($slug);
