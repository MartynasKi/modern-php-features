<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Uri\WhatWg\Url');

use Uri\WhatWg\Url;

section('Browser rules fix sloppy input');
$url = Url::parse('HTTPS://Bücher.example/a b?q=ä', null, $errors);
dump($url->toAsciiString());
dump($url->toUnicodeString());

section('International hosts');
dump($url->getAsciiHost());
dump($url->getUnicodeHost());

section('Soft errors are reported but do not stop parsing');
foreach ($errors as $error) {
    text("{$error->type->name}: '{$error->context}'");
}

section('Backslashes count as slashes, like in a browser');
dump(Url::parse('https:\\example.com\path')->toAsciiString());

section('A missing scheme is a hard error');
dump(Url::parse('//example.com/path'));
