# 013: Lazy Objects

A lazy object looks like a normal object, but the expensive work to build it
waits until you actually use it.

## How lazy objects work

Lazy objects arrived in PHP 8.4. You create them with `ReflectionClass`.

- `newLazyGhost($initializer)` returns an object of the real class with no
  state yet. On first use, PHP calls the initializer with the object itself,
  so it can run the constructor or fill in properties.
- `newLazyProxy($factory)` returns an empty object of the class. On first use,
  PHP calls the factory, which returns the real instance. From then on, the
  proxy forwards every property access to it.

Some details that matter:

- Only touching the object's state starts the initialization. That means
  reading or writing a property, `var_dump()`, `clone` and similar. Calling a
  method does not count until the method reads a property.
- A class with no properties has nothing to wait for, so its lazy object
  counts as initialized right away.
- `isUninitializedLazyObject()` tells you if the work happened yet.
  `setRawValueWithoutLazyInitialization()` sets one property, such as an ID,
  without starting it.
- A ghost is the real object, so `===` comparisons stay simple. A proxy and
  its real instance are two different objects.

Doctrine uses lazy ghosts for entities it has not loaded yet. Symfony's
dependency injection container uses lazy services, so a heavy service that is
injected but never used is never built. Before PHP 8.4, libraries generated
proxy classes with code generation.

## Run the examples

This project uses PHP 8.5, but lazy objects work since PHP 8.4. Run these
commands from the repository root after `composer install`.

### [01-lazy-ghost.php](examples/01-lazy-ghost.php)

A lazy `User` with an ID set in advance, like an ORM reference. Reading `$id`
does not run the query. Reading `$name` calls the initializer, which runs the
constructor.

```shell
php ./013-lazy-objects/examples/01-lazy-ghost.php
```

### [02-lazy-proxy.php](examples/02-lazy-proxy.php)

The `Mailer` connects in its constructor. The proxy waits until the first
`send()` reads `$host`. The second `send()` reuses the same real instance,
which is a different object from the proxy.

```shell
php ./013-lazy-objects/examples/02-lazy-proxy.php
```

### [03-lazy-container.php](examples/03-lazy-container.php)

A tiny container that hands out lazy proxies. The script gets a `Database`
and a `Cache` but only uses the database, so the cache never connects.

```shell
php ./013-lazy-objects/examples/03-lazy-container.php
```

The PHP manual has more on [lazy objects](https://www.php.net/manual/en/language.oop5.lazy-objects.php).
