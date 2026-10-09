# 998: Tips and Tricks

Small things that make everyday PHP shorter, safer or more precise. Each tip
stands alone, so read them in any order.

This project uses PHP 8.5. Each tip names the PHP version it needs. Run the
examples from the repository root after `composer install`.

## Built-in functions as callables

Since PHP 8.1, `abs(...)` turns any built-in function into a closure. That
means you can pass math and string functions straight to `array_map()`,
`array_reduce()`, `usort()` or `array_filter()`, without wrapping them in
`fn($n) => abs($n)`. See [009: First-Class Callable Syntax](../009-first-class-callables/README.md)
for how this works.

The callback must accept the arguments it receives. `max(...)` takes two
values, so it fits `array_reduce()`, where each call gets the carry and the
next item.

### [01-math-callables.php](examples/01-math-callables.php)

Maps `abs(...)` and `round(...)` over a list of numbers and finds the largest
with `array_reduce()` and `max(...)`. Then it sorts file names with
`strcmp(...)` and `strnatcmp(...)`, where natural order puts `img2` before
`img10`, and filters with `is_numeric(...)`.

```shell
php ./998-tips-and-tricks/examples/01-math-callables.php
```

## hrtime() instead of microtime()

`microtime(true)` reads the wall clock and returns seconds as a float. Two
problems for measuring time:

- The wall clock can jump. An NTP sync or a manual clock change between start
  and end gives a wrong or even negative duration.
- A float holding today's timestamp can only change in steps of about 238
  nanoseconds, so short measurements lose precision.

`hrtime(true)` (PHP 7.3) reads a monotonic clock and returns nanoseconds as an
int. It only moves forward and its value has no meaning on its own, so use it
for durations, never for dates. `hrtime()` without `true` returns seconds and
nanoseconds in an array.

### [02-hrtime.php](examples/02-hrtime.php)

Times the same loop with both functions, then works out the smallest step a
`microtime()` float can represent right now.

```shell
php ./998-tips-and-tricks/examples/02-hrtime.php
```

## Array tricks

- `[$a, $b] = [$b, $a]` swaps two variables.
- Keyed destructuring works in `foreach`:
  `foreach ($users as ['name' => $name])`.
- Since PHP 8.1, spreading arrays with string keys works too, and later keys
  win like in `array_merge()`.
- `array_column($rows, 'name', 'id')` builds an id to name map.
- `array_map(null, $a, $b)` zips two arrays into pairs.

### [03-array-tricks.php](examples/03-array-tricks.php)

Shows each trick on a small list of users and settings.

```shell
php ./998-tips-and-tricks/examples/03-array-tricks.php
```

## Numeric literals

Since PHP 7.4, underscores separate digits in number literals: `1_000_000`.
PHP 8.1 added the `0o` prefix for octal, which is clearer than a leading zero.
`0b` writes binary.

Underscores only work in code. `(int) '1_000'` is `1` and `is_numeric()`
rejects it.

### [04-numeric-literals.php](examples/04-numeric-literals.php)

Prints numbers written with underscores, octal, hex and binary literals, then
shows that a string with underscores is not a number.

```shell
php ./998-tips-and-tricks/examples/04-numeric-literals.php
```

## Division

- `/` returns a float unless the result is a whole number.
- `intdiv()` always returns an int and drops the remainder.
- Dividing by zero throws `DivisionByZeroError`. `%` and `intdiv()` throw
  since PHP 7 and `/` since PHP 8.0.
- `fdiv()` (PHP 8.0) follows IEEE 754 and returns `INF`, `-INF` or `NAN`
  instead of throwing.
- `%` works on ints. Use `fmod()` for floats.

### [05-safe-division.php](examples/05-safe-division.php)

Compares `/` and `intdiv()`, catches a division by zero and shows what
`fdiv()` returns instead.

```shell
php ./998-tips-and-tricks/examples/05-safe-division.php
```

## get_debug_type() and $object::class

`gettype()` returns old names such as `integer`, `double` and `object`.
`get_debug_type()` (PHP 8.0) returns the names you write in type declarations
and the class name for objects. It is the better choice for error messages.

Since PHP 8.0, `$object::class` works on objects too, so `get_class()` is not
needed.

### [06-debug-type-and-class.php](examples/06-debug-type-and-class.php)

Prints both type names for eight values side by side.

```shell
php ./998-tips-and-tricks/examples/06-debug-type-and-class.php
```

## JSON flags

- `JSON_PRETTY_PRINT` adds new lines and indentation.
- `JSON_UNESCAPED_UNICODE` keeps letters such as `ė` as they are instead of
  `ė`.
- `JSON_UNESCAPED_SLASHES` keeps `/` instead of `\/`.
- `JSON_THROW_ON_ERROR` (PHP 7.3) throws a `JsonException` instead of
  returning `null` and leaving you to check `json_last_error()`.

`JSON_THROW_ON_ERROR` matters most for `json_decode()`, because `null` is also
a valid decoded value.

### [07-json-flags.php](examples/07-json-flags.php)

Encodes a Lithuanian address with and without the readability flags, then
decodes broken JSON with and without `JSON_THROW_ON_ERROR`.

```shell
php ./998-tips-and-tricks/examples/07-json-flags.php
```
