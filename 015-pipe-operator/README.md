# 015: Pipe Operator

The pipe operator `|>` passes a value into a callable, so a chain of calls
reads left to right.

## How it works

The pipe operator arrived in PHP 8.5. `$value |> callable` calls the callable
with `$value` as its only argument and returns the result.

```php
$slug = $title
    |> trim(...)
    |> strtolower(...)
    |> (fn($text) => str_replace(' ', '-', $text));
```

It replaces two common styles:

- Nested calls such as `str_replace(' ', '-', strtolower(trim($title)))`,
  which you read from the inside out.
- A temporary variable that is reassigned on every line.

## What can go on the right side

The right side must be a callable that accepts exactly one argument.

- First-class callables such as `trim(...)` and `$object->method(...)`.
- Strings such as `'trim'`, invokable objects and closures.
- An arrow function for anything that needs extra arguments. It must be
  wrapped in parentheses, otherwise PHP stops with a fatal error.

These do not work:

- A function that needs more required arguments, such as `explode(...)`. It
  throws an `ArgumentCountError`.
- A function that takes its argument by reference, such as `sort(...)`.
- A plain call such as `trim()` without `(...)`. That runs `trim()` first and
  pipes into its result.

## Compared to Laravel collections

Laravel's `collect($orders)->filter(...)->map(...)->implode(', ')` gives a
similar left-to-right flow, but only for methods the `Collection` class has.
The pipe operator works with any function and any type, without a wrapper
object.

The downside shows with array functions. Most of them need more than one
argument and some take the array first, others second. Each step needs its
own arrow function, so a pipe chain over arrays is often longer than the
collection version. Pipes shine with single-argument functions.

## Run the examples

This project uses PHP 8.5, which is also the version that introduced the pipe
operator. Run these commands from the repository root after
`composer install`.

### [01-basic-pipe.php](examples/01-basic-pipe.php)

Turns a title into a slug three ways: nested calls, a temporary variable and a
pipe chain.

```shell
php ./015-pipe-operator/examples/01-basic-pipe.php
```

### [02-right-hand-side-limits.php](examples/02-right-hand-side-limits.php)

Pipes into a first-class callable, a string, a method and an invokable
`Formatter`. `explode()` needs an arrow function. Piping straight into
`explode(...)` or `sort(...)` throws.

```shell
php ./015-pipe-operator/examples/02-right-hand-side-limits.php
```

### [03-compared-to-collections.php](examples/03-compared-to-collections.php)

Finds customers with orders of at least 100 with nested array functions and
then with pipes. Each pipe step wraps an array function in an arrow function.

```shell
php ./015-pipe-operator/examples/03-compared-to-collections.php
```

The PHP manual has more on the [pipe operator](https://www.php.net/manual/en/language.operators.functional.php).
