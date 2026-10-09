<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('UnhandledMatchError');

function httpMessage(int $code): string
{
    return match ($code) {
        200 => 'OK',
        404 => 'Not Found',
    };
}

section('A handled value');
dump(httpMessage(404));

section('No arm matches and there is no default');
try {
    httpMessage(500);
} catch (UnhandledMatchError $error) {
    dump($error->getMessage());
}

function httpMessageWithSwitch(int $code): ?string
{
    switch ($code) {
        case 200:
            return 'OK';
        case 404:
            return 'Not Found';
    }

    return null;
}

section('switch would return nothing here');
dump(httpMessageWithSwitch(500));
