<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Uri\Rfc3986\Uri');

use Uri\InvalidUriException;
use Uri\Rfc3986\Uri;

$uri = new Uri('https://ann:secret@Example.COM:8080/docs/../blog/post?page=2#comments');

section('Read each part');
dump($uri->getScheme());
dump($uri->getUsername());
dump($uri->getHost());
dump($uri->getPort());
dump($uri->getPath());
dump($uri->getQuery());
dump($uri->getFragment());

section('Normalized vs raw');
dump($uri->toString());
dump($uri->toRawString());

section('Withers return a changed copy');
$next = $uri
    ->withUserInfo(null)
    ->withPath('/blog/next-post')
    ->withQuery(null)
    ->withFragment(null);
dump($next->toString());

section('Resolve a relative link');
dump($uri->resolve('../about')->toString());

section('Invalid input');
dump(Uri::parse('https://exa mple.com'));

try {
    new Uri('https://exa mple.com');
} catch (InvalidUriException $exception) {
    dump($exception->getMessage());
}
