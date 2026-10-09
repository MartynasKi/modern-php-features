<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Stream errors as exceptions');

section('Before: a warning and false');
$file = @fopen('missing.txt', 'r');
dump($file);
dump(error_get_last()['message']);

section('PHP 8.6: ask for a StreamException');
$context = stream_context_create([
    'stream' => ['error_mode' => StreamErrorMode::Exception],
]);

try {
    fopen('missing.txt', 'r', false, $context);
} catch (StreamException $exception) {
    dump($exception->getMessage());

    foreach ($exception->getErrors() as $error) {
        text("{$error->code->name} from the {$error->wrapperName} wrapper");
    }
}

section('Or stay silent and read the errors afterwards');
$context = stream_context_create([
    'stream' => [
        'error_mode' => StreamErrorMode::Silent,
        'error_store' => StreamErrorStore::All,
    ],
]);

dump(fopen('missing.txt', 'r', false, $context));
dump(array_map(fn(StreamError $error) => $error->code, stream_last_errors()));
