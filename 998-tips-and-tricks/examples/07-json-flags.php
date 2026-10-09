<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('json_encode() and json_decode() flags');

$data = ['city' => 'Kaunas', 'street' => 'Laisvės al.', 'url' => 'https://example.com/a'];

section('The defaults escape Unicode and slashes');
dump(json_encode($data));

section('Readable output');
io()->writeln(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

section('Errors are silent by default');
dump(json_decode('{broken'));
dump(json_last_error_msg());

section('JSON_THROW_ON_ERROR turns them into exceptions');
try {
    json_decode('{broken', flags: JSON_THROW_ON_ERROR);
} catch (JsonException $exception) {
    dump($exception->getMessage());
}
