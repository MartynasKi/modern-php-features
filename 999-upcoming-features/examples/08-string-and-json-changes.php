<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Small string and JSON changes');

section('grapheme_strrev() reverses what you see, not bytes');
$word = 'Ąžuolas';
dump(grapheme_strrev($word));
dump(mb_check_encoding(strrev($word), 'UTF-8'));

section('trim() now removes form feeds too');
dump(trim("\fReport\f"));

section('JSON errors say where the problem is');
$json = <<<'JSON'
    {
        "name": "Ann",
        "age":
    }
    JSON;

try {
    json_decode($json, flags: JSON_THROW_ON_ERROR);
} catch (JsonException $exception) {
    dump($exception->getMessage());
}
