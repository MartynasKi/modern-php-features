# 009: First-Class Callable Syntax

`strlen(...)` turns a function or method into a `Closure` object.

## How it works

First-class callable syntax arrived in PHP 8.1. Write a normal call and put
`...` where the arguments go.

- `strlen(...)` for a function.
- `$object->method(...)` for an object method. The closure keeps `$object`.
- `Foo::method(...)` for a static method.

The result is always a `Closure`, so you can store it, pass it to
`array_map()` or call it later.

## How it differs from older callables

Before PHP 8.1 there were several ways to describe a callable:

- A string such as `'strlen'` or `'Foo::method'`.
- An array such as `[$object, 'method']`.
- `Closure::fromCallable('strlen')`, which turns one of those into a closure.

The new syntax fixes a few problems with these:

- A string or array is only data. A typo fails when the callable is finally
  called, maybe far away. `strtoupperr(...)` fails on the line where you
  wrote it.
- IDEs and static analysis see a real function call, so renames and "find
  usages" work.
- Scope is checked where the closure is created. A class can hand out a
  closure to its private method and anyone can call it.
  `Closure::fromCallable()` does the same, but needs a string or array.

`Closure::fromCallable()` still works and is useful when the name is only
known at runtime.

## Run the examples

This project uses PHP 8.5, but the syntax works since PHP 8.1. Run these
commands from the repository root after `composer install`.

### [01-creating-closures.php](examples/01-creating-closures.php)

Creates closures from `strlen()`, from the `price()` method of a `Formatter`
object and from the static `slug()` method. The `price` closure remembers its
`EUR` formatter when `array_map()` calls it.

```shell
php ./009-first-class-callables/examples/01-creating-closures.php
```

### [02-compared-to-older-callables.php](examples/02-compared-to-older-callables.php)

A string, `Closure::fromCallable()` and `(...)` all give the same result. A
typo in the string only fails when it is called, while `strtoupperr(...)` fails
right away. `Report` hands out a closure to its private `format()` method, but
the array callable `[$report, 'format']` is not callable from outside.

```shell
php ./009-first-class-callables/examples/02-compared-to-older-callables.php
```

The PHP manual has more on [first-class callable syntax](https://www.php.net/manual/en/functions.first_class_callable_syntax.php).
