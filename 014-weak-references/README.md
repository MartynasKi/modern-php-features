# 014: Weak References

A weak reference points to an object without keeping it alive.

## Object lifetime

PHP frees an object when its refcount drops to zero, as covered in
[001: Fundamentals](../001-fundamentals/README.md). Every variable, array item
and property that holds the object adds one to that count.

That is a problem for caches and registries. If a cache stores the object, the
object can never be freed while the cache lives. In a long-running worker,
such as Laravel Octane or a queue worker, the cache keeps growing.

## How weak references work

- `WeakReference` (PHP 7.4) wraps one object. `get()` returns the object or
  `null` once it has been destroyed. It does not add to the refcount.
- `WeakMap` (PHP 8.0) is a map with objects as keys. When a key object is
  destroyed, its entry disappears from the map. Values are stored normally.

A `WeakMap` is the right tool to attach extra data to objects you do not own,
such as computed values or metadata, without leaking memory.

Keys must be objects. Values are strong references, so a value that points
back to its own key keeps the entry alive.

## Run the examples

This project uses PHP 8.5, but `WeakMap` works since PHP 8.0. Run these
commands from the repository root after `composer install`.

### [01-weak-reference.php](examples/01-weak-reference.php)

A weak reference to a `Connection`. It survives while any normal variable
holds it. After the last one is unset, the destructor runs and `get()` returns
`null`.

```shell
php ./014-weak-references/examples/01-weak-reference.php
```

### [02-weak-map.php](examples/02-weak-map.php)

Counts visits per `Request` object. Unsetting one request removes its entry.
A string key throws a `TypeError`.

```shell
php ./014-weak-references/examples/02-weak-map.php
```

### [03-cache-without-leaks.php](examples/03-cache-without-leaks.php)

Two caches for invoice totals. `ArrayCache` stores each invoice next to its
total, so 1,000 throwaway invoices stay in memory. `WeakCache` uses the
invoice as a `WeakMap` key, so every entry disappears with its invoice.
Exact memory numbers depend on your build.

```shell
php ./014-weak-references/examples/03-cache-without-leaks.php
```

The PHP manual has more on [WeakReference](https://www.php.net/manual/en/class.weakreference.php)
and [WeakMap](https://www.php.net/manual/en/class.weakmap.php).
