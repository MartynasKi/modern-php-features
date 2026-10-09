# 008: Union, Intersection and DNF Types

PHP types can combine other types. A value can be one of several types, all
of several types or a mix of both.

## How the type combinations work

- Union types (PHP 8.0): `int|string` accepts either type. `?string` is short
  for `string|null`.
- Intersection types (PHP 8.1): `Countable&Traversable` accepts only objects
  that implement both. Intersections only work with classes and interfaces.
- DNF types (PHP 8.2): an intersection in parentheses joined with a union, such
  as `(Countable&Traversable)|null`. DNF stands for disjunctive normal form:
  ORs of ANDs.
- `false` (PHP 8.0) and `null` (PHP 8.0) started as union-only types. Since
  PHP 8.2, `null`, `false` and `true` can stand alone.
- `never` (PHP 8.1) is a return type for functions that always throw or exit.
- `static` (PHP 8.0) as a return type means "the class this was called on",
  which keeps fluent chains typed in subclasses.

With strict types, a union does not convert values. Passing `4.2` to
`int|string` throws a `TypeError`. Without strict types, PHP tries `int`,
then `float`, then `string`, then `bool`.

Error messages may print a union in a different order than you wrote it,
such as `string|int` for `int|string`. The order has no meaning.

## Run the examples

This project uses PHP 8.5. Each script needs the PHP version listed above for
its feature. Run these commands from the repository root after
`composer install`.

The scripts cut type error messages at `, called in` to hide long file paths.

### [01-union-types.php](examples/01-union-types.php)

`findUser()` takes an `int` or a `string` but rejects a float. `half()` keeps
the number type you pass.

```shell
php ./008-union-intersection-dnf-types/examples/01-union-types.php
```

### [02-intersection-types.php](examples/02-intersection-types.php)

`summary()` needs something it can count and loop over. `ArrayIterator` and
`ArrayObject` pass. A plain array and a generator do not.

```shell
php ./008-union-intersection-dnf-types/examples/02-intersection-types.php
```

### [03-dnf-types.php](examples/03-dnf-types.php)

`countOrNothing()` accepts `null` or an object that is both `Countable` and
`Traversable`. An object that is only `Countable` fails.

```shell
php ./008-union-intersection-dnf-types/examples/03-dnf-types.php
```

### [04-null-false-true.php](examples/04-null-false-true.php)

`firstWord()` returns `string|false` in the style of `strpos()`. The other
functions use `true`, `null` and `false` as standalone types. `false` really
means only `false`, so passing `true` throws.

```shell
php ./008-union-intersection-dnf-types/examples/04-null-false-true.php
```

### [05-never-and-static.php](examples/05-never-and-static.php)

`abort()` returns `never`, so it fits on the right side of `??`. Then
`UserQuery` extends a query builder. Thanks to the `static` return type, PHP
knows `where()` returns a `UserQuery`, so `active()` can follow it.

```shell
php ./008-union-intersection-dnf-types/examples/05-never-and-static.php
```

The PHP manual has more on [type declarations](https://www.php.net/manual/en/language.types.declarations.php).
