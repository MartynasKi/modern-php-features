# 019: Typed Class Constants

Class constants can have types, can be final and can be read by a name that is
only known at runtime.

## How it works

- Final class constants (PHP 8.1): `final public const PRIMARY_KEY = 'id'`
  stops child classes from overriding it. Since PHP 8.1, interface constants
  can also be overridden unless they are final.
- Typed class constants (PHP 8.3): `public const string TABLE = 'users'`.
  The value must match the type and a child class must keep a compatible
  type.
- Dynamic class constant fetch (PHP 8.3): `Config::{$name}` reads a constant
  whose name is in a variable. It works for enum cases too.
- Enums (PHP 8.1) can have constants. Since PHP 8.3, a constant can use the
  enum itself as its type, as in `public const self DEFAULT = self::Draft`.

Any type works except `void`, `never` and `callable`.

Without a type, a child class can quietly change `TABLE` from a string to an
int. With a type, PHP stops with an error like this when the class is
declared:

```text
Fatal error: Type of Admin::TABLE must be compatible with User::TABLE of type string
```

Overriding a final constant fails in a similar way:

```text
Fatal error: Post::PRIMARY_KEY cannot override final constant Model::PRIMARY_KEY
```

Neither can be caught with `try`.

## Run the examples

This project uses PHP 8.5. Each feature needs the PHP version listed above.
Run these commands from the repository root after `composer install`.

### [01-typed-constants.php](examples/01-typed-constants.php)

`User` has `string`, `int`, `array` and `?string` constants and implements an
interface with a typed `TABLE`. Reflection reports the constant type. The
commented `Admin` class tries to change `TABLE` to an int. Uncomment it to see
the first error above.

```shell
php ./019-typed-class-constants/examples/01-typed-constants.php
```

### [02-final-constants.php](examples/02-final-constants.php)

`Post` overrides `DATE_FORMAT` but not the final `PRIMARY_KEY`. Uncomment the
line in `Post` to see the second error above.

```shell
php ./019-typed-class-constants/examples/02-final-constants.php
```

### [03-dynamic-constant-fetch.php](examples/03-dynamic-constant-fetch.php)

Reads `Config` constants with `constant()` and with the new `::{$name}`
syntax, then picks an enum case by name. A missing constant throws an `Error`.

```shell
php ./019-typed-class-constants/examples/03-dynamic-constant-fetch.php
```

### [04-constants-in-enums.php](examples/04-constants-in-enums.php)

`Status` has a typed `DEFAULT` constant that points to a case and an
`EDITABLE` list of cases that `isEditable()` checks.

```shell
php ./019-typed-class-constants/examples/04-constants-in-enums.php
```

The PHP manual has more on [class constants](https://www.php.net/manual/en/language.oop5.constants.php).
