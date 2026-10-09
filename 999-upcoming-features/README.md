# 999: Upcoming Features

A look at PHP 8.6 before its stable release. Everything here is new in
PHP 8.6.

PHP 8.6 is not stable yet. I tested these examples on **PHP 8.6.0RC2**.
Features can still change or disappear before the final release, so treat
this topic as a preview. When PHP 8.6 is stable, these examples can move into
regular topics.

## Install PHP 8.6 with Laravel Herd

The easiest way to try a preview version next to your normal PHP is
[Laravel Herd](https://herd.laravel.com/). Herd installs each PHP version
side by side and adds a command for each one, such as `php85` and `php86`.
Your default `php` stays on the stable version.

1. Install PHP 8.6 in Herd's PHP settings, or run `herd php:install 8.6`.
2. Check the version.

   ```shell
   php86 -v
   ```

3. Run the examples with `php86` instead of `php`.

Run `herd php:update 8.6` to get the next release candidate. In Git Bash,
type `php86.bat` instead of `php86`.

The rest of this project needs PHP 8.5, so plain `php` would fail here with
syntax errors or missing functions.

## Run the examples

Run these commands from the repository root after `composer install`.

### [01-partial-function-application.php](examples/01-partial-function-application.php)

Partial function application lets you call a function with `?` in place of
an argument. Instead of calling it, PHP returns a `Closure` that waits for
the missing argument. `...` leaves all remaining arguments open.

```php
$slug = str_replace(' ', '-', ?);
$slug('modern php features'); // "modern-php-features"
```

This fixes the biggest limit of the [pipe operator](../015-pipe-operator/README.md):
`|> str_replace(' ', '-', ?)` works without wrapping it in an arrow function.
The script also uses `?` with named arguments and shows that calling the
closure without its open argument throws an `ArgumentCountError`.

```shell
php86 ./999-upcoming-features/examples/01-partial-function-application.php
```

### [02-clamp.php](examples/02-clamp.php)

`clamp($value, $min, $max)` keeps a value inside a range. It replaces the
`max($min, min($value, $max))` trick that is easy to get backwards. It works
with ints, floats and strings and throws a `ValueError` if `$min` is greater
than `$max`.

```shell
php86 ./999-upcoming-features/examples/02-clamp.php
```

### [03-duration.php](examples/03-duration.php)

`Time\Duration` is an immutable length of time with nanosecond precision.
Create one from hours, minutes, seconds, smaller units or an ISO 8601 string
such as `PT1H30M`. `add()`, `sub()`, `multiplyBy()` and `divideBy()` return a
new duration and `Duration::compare()` returns `-1`, `0` or `1`.

The last part wraps an `hrtime()` measurement, from
[998: Tips and Tricks](../998-tips-and-tricks/README.md), in a duration.

```shell
php86 ./999-upcoming-features/examples/03-duration.php
```

### [04-readonly-property-defaults.php](examples/04-readonly-property-defaults.php)

A readonly property can now have a default value. The default counts as its
one allowed write, so the constructor cannot change it afterwards. Use a
default for values that are truly fixed and the constructor for the rest.

```shell
php86 ./999-upcoming-features/examples/04-readonly-property-defaults.php
```

### [05-override-class-constants.php](examples/05-override-class-constants.php)

`#[\Override]` from [017](../017-nodiscard-override-deprecated/README.md) now
works on class constants too. The commented `Post` class has a typo in
`TABEL`. Uncomment it to see this error:

```text
Fatal error: Post::TABEL has #[\Override] attribute, but no matching parent constant exists
```

```shell
php86 ./999-upcoming-features/examples/05-override-class-constants.php
```

### [06-stream-errors.php](examples/06-stream-errors.php)

File and network functions such as `fopen()` used to return `false` and raise
a warning. A stream context can now ask for a `StreamException` instead, or
stay silent and collect `StreamError` objects for `stream_last_errors()`.
Each error has a `StreamErrorCode` enum case such as `OpenFailed`.

```shell
php86 ./999-upcoming-features/examples/06-stream-errors.php
```

### [07-uri-builder.php](examples/07-uri-builder.php)

The [URI extension](../021-uri-extension/README.md) gets `UriBuilder` and
`UrlBuilder` for building a URI from parts, `getUriType()` and
`getHostType()` for checking what kind of URI or host you have, and
`url_percent_encode()` for encoding one value.

```shell
php86 ./999-upcoming-features/examples/07-uri-builder.php
```

### [08-string-and-json-changes.php](examples/08-string-and-json-changes.php)

Three small changes:

- `grapheme_strrev()` reverses a string by the characters you see.
  `strrev()` reverses bytes and breaks letters such as `Ą`.
- `trim()`, `ltrim()` and `rtrim()` now also remove the form feed character
  `\f` by default.
- JSON errors now include a position, such as `Syntax error near location 4:1`.

```shell
php86 ./999-upcoming-features/examples/08-string-and-json-changes.php
```

### [09-constructor-return-deprecation.php](examples/09-constructor-return-deprecation.php)

`return 'something';` inside `__construct()` or `__destruct()` is now
deprecated. The value was always ignored, so it usually points to a bug. PHP
raises this deprecation while compiling the class, so the script compiles it
with `eval()` after setting the error handler.

```shell
php86 ./999-upcoming-features/examples/09-constructor-return-deprecation.php
```

## Other PHP 8.6 changes

PHP 8.6 has more changes that are not covered here, such as a
`SortDirection` enum, an `Io\Poll` API for waiting on many streams,
`ReflectionProperty::isReadable()` and `isWritable()`, doc comments on
parameters and safer session defaults.

The full list is on the [PHP RFC page](https://wiki.php.net/rfc) and in
[PHP 8.6 on PHP.Watch](https://php.watch/versions/8.6).
