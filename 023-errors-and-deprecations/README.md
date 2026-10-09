# 023: Errors and Deprecations

How PHP reports problems today, and how to deal with deprecation notices when
you upgrade.

## The Throwable hierarchy

Since PHP 7, most engine errors are thrown as objects instead of fatal errors.
Everything you can throw implements `Throwable`, which has two branches:

```text
Throwable
├── Exception        problems your code expects and handles
│   ├── RuntimeException, LogicException, JsonException, ...
└── Error            mistakes in the code itself
    ├── TypeError
    │   └── ArgumentCountError
    ├── ValueError
    ├── ArithmeticError
    │   └── DivisionByZeroError
    └── UnhandledMatchError, ...
```

`catch (Exception $e)` does not catch an `Error`. Use `catch (Throwable $e)`
at the top level, such as an error handler or a queue worker, when you need
to catch everything. Catch specific classes everywhere else.

## TypeError and ValueError

- `TypeError` means the value has the wrong type. With strict types, this
  includes calling `strlen(42)`.
- `ArgumentCountError` extends `TypeError`. It means too few arguments, or too
  many for a built-in function.
- `ValueError` (PHP 8.0) means the type is right but the value is not, such as
  a negative count for `str_repeat()`.

Before PHP 8.0, many built-in functions returned `false` or `null` with a
warning for bad arguments. Now they throw, so mistakes fail loudly.

## Deprecations

A deprecation notice means "this still works, but will stop working in a
future version". It is the upgrade warning you get before something breaks.

- Dynamic properties (deprecated in PHP 8.2): writing to an undeclared
  property, which is often a typo. Declare the property, add
  `#[AllowDynamicProperties]` to the class or use `stdClass`, an array or a
  `WeakMap`.
- Implicit nullable types (deprecated in PHP 8.4): `string $name = null`
  quietly made the type nullable. Write `?string $name = null`.
- Null as an array offset (deprecated in PHP 8.5): `$array[null]` silently
  meant `$array['']`. Use an empty string or a real key.

## How to read a deprecation notice

```text
Deprecated: Creation of dynamic property Cart::$total is deprecated in /app/Cart.php on line 29
```

- `Deprecated` is the level. `E_DEPRECATED` comes from PHP itself.
  `E_USER_DEPRECATED` comes from a library through `trigger_error()` or
  `#[\Deprecated]`.
- The message names the feature and often the fix.
- The file and line point to where it happened. In a framework, the line can
  be inside vendor code that your code called. Check the stack trace to find
  your own call.

Some tips for upgrades:

- Make sure `error_reporting` includes `E_DEPRECATED` in development and CI.
  Production configs often hide it.
- Run your test suite on the new PHP version first. Laravel and PHPUnit can
  log or list deprecations.
- Fix your own code first. For vendor code, update the package.

## Run the examples

This project uses PHP 8.5. Run these commands from the repository root after
`composer install`.

### [01-throwable-hierarchy.php](examples/01-throwable-hierarchy.php)

Prints the parents of common exception and error classes. Then a
`DivisionByZeroError` slips past `catch (Exception)`, while
`catch (Throwable)` catches both kinds.

```shell
php ./023-errors-and-deprecations/examples/01-throwable-hierarchy.php
```

### [02-type-and-value-errors.php](examples/02-type-and-value-errors.php)

Calls built-in functions with wrong types, too few arguments and bad values,
and prints which error each one throws.

```shell
php ./023-errors-and-deprecations/examples/02-type-and-value-errors.php
```

### [03-dynamic-properties.php](examples/03-dynamic-properties.php)

A typo in `$user->nmae` creates a new property, raises a deprecation and
leaves `$name` unchanged. Then three ways to avoid the notice.

```shell
php ./023-errors-and-deprecations/examples/03-dynamic-properties.php
```

### [04-implicit-nullable.php](examples/04-implicit-nullable.php)

`greetOld()` uses `string $name = null` and raises the PHP 8.4 deprecation.
PHP raises it while compiling the function, so the script compiles it with
`eval()` after setting the error handler. `greet()` shows the fix.

```shell
php ./023-errors-and-deprecations/examples/04-implicit-nullable.php
```

### [05-reading-deprecations.php](examples/05-reading-deprecations.php)

Collects three notices the way a logger would: a dynamic property, a null
array offset and a library deprecation from `trigger_error()`. Each one has
its level, message and line.

```shell
php ./023-errors-and-deprecations/examples/05-reading-deprecations.php
```

The PHP manual has more on [Throwable](https://www.php.net/manual/en/class.throwable.php)
and [backward incompatible changes](https://www.php.net/manual/en/migration85.incompatible.php).
