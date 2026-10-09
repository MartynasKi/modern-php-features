# 004: Enums

An enum is a type with a fixed list of possible values, called cases.

## How enums work

Enums arrived in PHP 8.1. Each case is an object and there is only ever one
instance of it, so `Status::Draft === Status::Draft` is always true.

- A pure enum has cases with names only.
- A backed enum gives each case a `string` or `int` value. This is the one
  you store in a database or send as JSON.
- Enums can have methods, static methods, constants and interfaces.
- Enums cannot have properties or be extended, and you cannot create cases
  with `new`.
- Every enum implements `UnitEnum`. Backed enums also implement `BackedEnum`.

Enums replace class constants such as `const STATUS_DRAFT = 'draft'`. A
constant is just a string, so any string slips through. An enum type only
accepts its own cases.

## Run the examples

This project uses PHP 8.5, but enums work since PHP 8.1. Run these commands
from the repository root after `composer install`.

### [01-pure-and-backed.php](examples/01-pure-and-backed.php)

`Suit` is a pure enum and `Status` is backed by strings. `cases()` lists all
cases in order. The last part shows which built-in interfaces each one
implements.

```shell
php ./004-enums/examples/01-pure-and-backed.php
```

### [02-methods-interfaces-constants.php](examples/02-methods-interfaces-constants.php)

`Status` implements a `HasLabel` interface, has instance and static methods
and a `DEFAULT` constant that points to a case. Inside a method, `$this` is
the current case.

```shell
php ./004-enums/examples/02-methods-interfaces-constants.php
```

### [03-from-and-tryfrom.php](examples/03-from-and-tryfrom.php)

Both turn a backing value into a case. `from()` throws a `ValueError` for an
unknown value. `tryFrom()` returns `null`, which works well with `??`. Use
`from()` when a bad value is a bug and `tryFrom()` for user input.

With strict types, the argument must match the backing type. Passing `'1'` to
an `int` backed enum throws a `TypeError`.

```shell
php ./004-enums/examples/03-from-and-tryfrom.php
```

### [04-match.php](examples/04-match.php)

`match` is the natural partner for enums. PHP does not check at compile time
that every case is covered. A missing case throws `UnhandledMatchError` only
when that case shows up. Static analysis tools such as PHPStan can catch it
earlier.

```shell
php ./004-enums/examples/04-match.php
```

### [05-json.php](examples/05-json.php)

`json_encode()` turns a backed case into its value. Decoding gives back a
plain string, so call `from()` to get the case again. A pure enum has no value
to encode, so `json_encode()` fails. `serialize()` works for both kinds and
`unserialize()` returns the same case object.

```shell
php ./004-enums/examples/05-json.php
```

The PHP manual has more on [enumerations](https://www.php.net/manual/en/language.enumerations.php).
