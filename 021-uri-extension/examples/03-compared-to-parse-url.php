<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Compared to parse_url()');

use Uri\Rfc3986\Uri;
use Uri\WhatWg\Url;

function compare(string $input): void
{
    $parsed = parse_url($input);

    dump([
        'input' => $input,
        'parse_url() host' => $parsed === false ? 'failed' : ($parsed['host'] ?? 'none'),
        'parse_url() path' => $parsed === false ? 'failed' : ($parsed['path'] ?? 'none'),
        'RFC 3986' => Uri::parse($input)?->toString() ?? 'invalid',
        'WHATWG' => Url::parse($input)?->toAsciiString() ?? 'invalid',
    ]);
}

section('parse_url() accepts a space in the host');
compare('https://exa mple.com/');

section('parse_url() and browsers disagree on the host');
compare('http://trusted.com\@evil.com/');

section('parse_url() does not normalize anything');
compare('https://EXAMPLE.com/a/./b/../c');

section('parse_url() gives up where browsers do not');
compare('http:///example.com');
