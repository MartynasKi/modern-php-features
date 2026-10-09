# 017: NoDiscard, Override and Deprecated

Three built-in attributes that make the engine catch mistakes for you. Unlike
custom attributes from [007: Attributes and Reflection](../007-attributes-and-reflection/README.md),
PHP itself reads these.

## #[Override]

Added in PHP 8.3 for methods and in PHP 8.5 for properties. It says "this
must override something from a parent class or interface".

The mistake it catches: a typo in the method name, or a parent method that
was renamed or removed. Without the attribute, PHP quietly treats the child
method as a new method and the parent version keeps running.

With the attribute, PHP stops with a fatal error when the class is loaded:

```text
Fatal error: InvoiceNotification::toMial() has #[\Override] attribute, but no matching parent method exists
```

This is a compile-time error, so it cannot be caught with `try`.

## #[Deprecated]

Added in PHP 8.4 for functions, methods and class constants, and in PHP 8.5
for global constants. Each use raises an `E_USER_DEPRECATED` notice with an
optional message and `since` version.

The mistake it catches: code that still calls an old API you plan to remove.
Before PHP 8.4, libraries called `trigger_error()` by hand inside the old
function. The attribute also lets IDEs and reflection see the deprecation
without running the code.

## #[NoDiscard]

Added in PHP 8.5. It warns when a function's return value is ignored.

The mistake it catches: calling a function that returns a new value and
expecting it to change the original. A classic case is
`DateTimeImmutable::modify()`, which PHP 8.5 marks with `#[\NoDiscard]`.

User functions raise `E_USER_WARNING` and built-in ones raise `E_WARNING`. If
you really want to ignore the result, cast the call to `(void)`.

## Run the examples

This project uses PHP 8.5. Each attribute needs the PHP version listed above.
Run these commands from the repository root after `composer install`.

### [01-override.php](examples/01-override.php)

`WelcomeNotification` overrides `toMail()` with `#[Override]`. The anonymous
class has a typo, `toMial()`, and no attribute, so the parent `toMail()` still
runs. The commented `InvoiceNotification` makes the same typo with the
attribute. Uncomment it to see the fatal error above.

```shell
php ./017-nodiscard-override-deprecated/examples/01-override.php
```

### [02-deprecated.php](examples/02-deprecated.php)

Calls a deprecated function, constant and method. A small error handler
prints each notice. The replacements stay quiet.

```shell
php ./017-nodiscard-override-deprecated/examples/02-deprecated.php
```

### [03-nodiscard.php](examples/03-nodiscard.php)

`withoutEmpty()` returns a filtered copy. Ignoring the result leaves the
original array unchanged and raises a warning. `(void)` silences it. The last
part makes the same mistake with `DateTimeImmutable::modify()`.

```shell
php ./017-nodiscard-override-deprecated/examples/03-nodiscard.php
```

The PHP manual has more on [predefined attributes](https://www.php.net/manual/en/reserved.attributes.php).
