# 021: URI Extension

PHP finally has a real URL parser that follows the standards, with objects you
can read and modify.

## How it works

The URI extension arrived in PHP 8.5 and is always available. It has two
classes for two standards:

- `Uri\Rfc3986\Uri` follows RFC 3986, the classic URI standard. It is strict
  and rejects anything invalid. Good for APIs and server-side validation.
- `Uri\WhatWg\Url` follows the WHATWG URL standard, which is what browsers
  use. It fixes sloppy input the way a browser would, such as spaces,
  backslashes and international domain names. Good when you need to see a URL
  exactly as a browser sees it.

Both classes work the same way:

- `new Uri($string)` throws on invalid input. `Uri::parse($string)` returns
  `null` instead.
- Getters such as `getHost()`, `getPath()` and `getQuery()` read each part.
- Withers such as `withPath()` and `withQuery()` return a modified copy. The
  objects are immutable.
- `resolve()` turns a relative link into a full URL.
- `Uri` normalizes on output with `toString()` and keeps the input with
  `toRawString()`. `Url` has `toAsciiString()` and `toUnicodeString()` for
  international domain names.

`Url::parse()` can also fill an `$errors` array with soft errors, which are
problems it fixed while parsing.

## Compared to parse_url()

`parse_url()` is old and does not follow either standard.

- It barely validates. A space in the host is fine.
- It can disagree with browsers. For `http://trusted.com\@evil.com/` it says
  the host is `evil.com`, while a browser goes to `trusted.com`. Code that
  checks redirect targets or blocks internal hosts can be fooled this way.
- It does not normalize, so `EXAMPLE.com` and `/a/./b/../c` come back as is.
- It returns an array. There is no built-in way to change one part and build
  the URL again.

## Run the examples

This project uses PHP 8.5, which is also the version that introduced the URI
extension. Run these commands from the repository root after
`composer install`.

### [01-rfc3986-uri.php](examples/01-rfc3986-uri.php)

Reads each part of a URL, compares the normalized and raw output and builds a
new URL with withers. `resolve()` turns `../about` into a full URL. A space in
the host makes `parse()` return `null` and the constructor throw.

```shell
php ./021-uri-extension/examples/01-rfc3986-uri.php
```

### [02-whatwg-url.php](examples/02-whatwg-url.php)

Parses a sloppy URL with an uppercase scheme, a German domain, a space and an
umlaut. The output is clean and the domain comes in ASCII and Unicode forms.
The space is reported as a soft error. Backslashes turn into slashes and a URL
without a scheme fails.

```shell
php ./021-uri-extension/examples/02-whatwg-url.php
```

### [03-compared-to-parse-url.php](examples/03-compared-to-parse-url.php)

Runs four tricky URLs through `parse_url()` and both new classes. The second
one is the security case where `parse_url()` and browsers disagree on the
host.

```shell
php ./021-uri-extension/examples/03-compared-to-parse-url.php
```

The PHP manual has more on the [URI extension](https://www.php.net/manual/en/book.uri.php).
