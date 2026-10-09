# 007: Attributes and Reflection

Attributes add structured metadata to classes, methods, properties and
parameters. Reflection reads that metadata at runtime. Together they power
routing, validation and ORM mapping in modern frameworks.

## How attributes work

Attributes arrived in PHP 8.0. They replace docblock annotations such as
`@Route("/users")`, which were only comments that libraries had to parse.

- An attribute is a normal class marked with `#[Attribute]`.
- Flags such as `Attribute::TARGET_METHOD` limit where it can go.
  `Attribute::IS_REPEATABLE` allows it more than once on the same target.
- You place it with `#[Route('/users')]`. The arguments look like constructor
  arguments and named arguments work too.
- On its own, an attribute does nothing. Some code must read it.

## How reflection reads them

`ReflectionClass`, `ReflectionMethod`, `ReflectionProperty` and the other
reflection classes have a `getAttributes()` method.

- `getAttributes(Route::class)` filters by attribute class.
- `getName()` and `getArguments()` return the raw data without creating
  anything.
- `newInstance()` creates the attribute object. Only now does PHP check that
  the class exists, the target is allowed and the arguments are valid.

Reflection is not free. Frameworks usually read attributes once and cache the
result, for example in Laravel's route cache.

## Run the examples

This project uses PHP 8.5, but attributes work since PHP 8.0. Run these
commands from the repository root after `composer install`.

### [01-custom-attribute.php](examples/01-custom-attribute.php)

Two attributes describe how `User` maps to a database table. The script reads
the raw `Table` data, creates the attribute object and then maps each property
to a column. A `Column` without a name falls back to the property name.

```shell
php ./007-attributes-and-reflection/examples/01-custom-attribute.php
```

### [02-target-validation.php](examples/02-target-validation.php)

`Route` only allows methods but sits on a class, and `#[MissingAttribute]`
points to a class that does not exist. PHP accepts both until
`newInstance()` runs, then throws an `Error`.

```shell
php ./007-attributes-and-reflection/examples/02-target-validation.php
```

### [03-tiny-router.php](examples/03-tiny-router.php)

A small router in the spirit of Symfony routes or Spatie's Laravel route
attributes. `routesFor()` collects every `#[Route]` on the controller methods.
`show()` has two routes thanks to `IS_REPEATABLE`. `dispatch()` turns
`{id}` into a regex group and passes the matches as named arguments.

```shell
php ./007-attributes-and-reflection/examples/03-tiny-router.php
```

The PHP manual has more on [attributes](https://www.php.net/manual/en/language.attributes.php)
and [reflection](https://www.php.net/manual/en/book.reflection.php).
