# 001: Fundamentals

Small PHP basics worth a second look. Some subtopics have an example script
and some are only short notes.

This project uses PHP 8.5. Run the examples from the repository root after
`composer install`.

## Strict types

`declare(strict_types=1);` arrived in PHP 7.0. It goes at the very top of a
file and only affects calls made from that file.

Without it, PHP quietly converts scalar values to match type declarations.
Passing `'5'` to an `int` parameter becomes `5`. With strict types, the same
call throws a `TypeError`. The one exception is that an `int` is still accepted
where a `float` is expected.

Every example in this project starts with strict types, so type mistakes show
up right away instead of being converted silently.

### 01-strict-types.php

`double(5)` returns `10`. `double('5')` throws a `TypeError`, which the
script catches and dumps.

```shell
php ./001-fundamentals/examples/01-strict-types.php
```

## Static variables

A `static` variable inside a function keeps its value between calls.
It is created on the first call and stays local to that function.

```php
function counter(): int
{
    static $count = 0;

    return ++$count;
}
```

Since PHP 8.3, the initial value can be any expression, such as a function
call. Before that, it had to be a constant value.

The `io()` helper in [bootstrap.php](../bootstrap.php) uses this to create the
SymfonyStyle object only once:

```php
function io(): SymfonyStyle
{
    static $io;

    return $io ??= new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
}
```

The first call creates the object. Every later call returns the same one.

### 02-static-variables.php

Call `counter()` three times and dump the results: `1`, `2` and `3`.

```shell
php ./001-fundamentals/examples/02-static-variables.php
```
