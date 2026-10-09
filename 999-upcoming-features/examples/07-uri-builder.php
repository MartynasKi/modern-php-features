<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('URI builders');

use Uri\Rfc3986\Uri;
use Uri\Rfc3986\UriBuilder;
use Uri\WhatWg\UrlBuilder;
use Uri\WhatWg\UrlPercentEncodingMode;

use function Uri\WhatWg\url_percent_encode;

section('Build a URI from parts instead of gluing strings');
$uri = new UriBuilder()
    ->setScheme('https')
    ->setHost('example.com')
    ->setPath('/search')
    ->setQuery('q=php')
    ->build();
dump($uri->toString());

section('New getters tell you what kind of URI and host you have');
dump($uri->getUriType());
dump($uri->getHostType());
dump(new Uri('http://127.0.0.1/')->getHostType());

section('Build a relative reference against a base URI');
$link = new UriBuilder()->setPath('../about')->build(new Uri('https://example.com/blog/post'));
dump($link->toString());

section('The WHATWG builder applies browser rules');
$url = new UrlBuilder()->setScheme('https')->setHost('bücher.example')->setPath('/a b')->build();
dump($url->toAsciiString());

section('Percent-encode a value for a form query');
dump(url_percent_encode('rock & roll', UrlPercentEncodingMode::FormQuery));
