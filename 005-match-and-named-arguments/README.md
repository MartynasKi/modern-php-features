# 005: Match and Named Arguments

Two PHP 8.0 features that make everyday code shorter and easier to read.

## How match works

`match` arrived in PHP 8.0. It looks like `switch` but behaves differently.

- It compares with `===`. With `switch`, `'1'` matches `case 1` and `false`
  matches `case 0` because it uses `==`.
- It is an expression, so it returns a value you can assign or return.
- Each arm runs one expression and there is no fall through, so there is no
  `break` to forget. Several values can share one arm with commas.
- If nothing matches and there is no `default`, it throws
  `UnhandledMatchError`. A `switch` silently does nothing.

`match (true)` is a neat replacement for long `if` and `elseif` chains. Each
arm is a condition and the first one that is `true` wins.

A `switch` is still fine when a branch needs several statements.

## How named arguments work

Named arguments also arrived in PHP 8.0. You pass a value by parameter name
instead of position: `createUser('Ann', sendWelcomeEmail: false)`.

- You can skip optional parameters and only set the ones you change.
- Order does not matter once you use names.
- Positional arguments must come before named ones.
- A string-keyed array spreads into named arguments with `...$options`.
- An unknown name or a name used twice throws an `Error`.

One catch: parameter names are now part of a function's public API. Renaming
a parameter can break code that calls it by name.

## How they help readability

Both remove guesswork for the reader. Compare these two calls:

```php
createUser('Ann', 'member', true, false);
createUser('Ann', sendWelcomeEmail: false);
```

The second one says what `false` means and skips the defaults. A `match`
shows the whole mapping in one place and fails loudly when a value is missing.

## Run the examples

This project uses PHP 8.5, but both features work since PHP 8.0. Run these
commands from the repository root after `composer install`.

### [01-match-vs-switch.php](examples/01-match-vs-switch.php)

The same mapping written with `switch` and `match`. With `switch`, the string
`'1'` matches `1` and `false` matches `0`. With `match`, both go to `default`.
Then a `switch` with a missing `break` returns the wrong price. The last two
parts show shared arms and `match (true)`.

```shell
php ./005-match-and-named-arguments/examples/01-match-vs-switch.php
```

### [02-unhandled-match-error.php](examples/02-unhandled-match-error.php)

`httpMessage()` has no arm for `500` and no `default`, so it throws
`UnhandledMatchError`. The `switch` version quietly falls through and returns
`null`.

```shell
php ./005-match-and-named-arguments/examples/02-unhandled-match-error.php
```

### [03-named-arguments.php](examples/03-named-arguments.php)

Calls `createUser()` with positional and named arguments, then uses names with
built-in functions such as `htmlspecialchars()`. It also spreads an array into
named arguments and shows the `Error` for an unknown name.

```shell
php ./005-match-and-named-arguments/examples/03-named-arguments.php
```

The PHP manual has more on [match](https://www.php.net/manual/en/control-structures.match.php)
and [named arguments](https://www.php.net/manual/en/functions.arguments.php#functions.named-arguments).
