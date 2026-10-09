# 018: New Array and String Functions

Small built-in functions that replace helpers most projects used to write
themselves or pull in from a framework.

## What each one does

| Function | PHP | Replaces |
| --- | --- | --- |
| `array_is_list()` | 8.1 | `array_keys($a) === range(0, count($a) - 1)` checks |
| `json_validate()` | 8.3 | `json_decode()` followed by a `json_last_error()` check |
| `array_find()`, `array_find_key()` | 8.4 | A `foreach` with an early `return`, Laravel's `Arr::first($array, $callback)` |
| `array_any()`, `array_all()` | 8.4 | `count(array_filter(...)) > 0`, Laravel's `contains()` and `every()` |
| `mb_trim()`, `mb_ltrim()`, `mb_rtrim()` | 8.4 | `preg_replace('/^\s+\|\s+$/u', '', $s)` |
| `mb_ucfirst()`, `mb_lcfirst()` | 8.4 | Hand-written multibyte `ucfirst()` helpers |
| `array_first()`, `array_last()` | 8.5 | `reset()` and `end()`, Laravel's `Arr::first()` and `Arr::last()` |

A few details:

- `array_find()` and `array_first()` return `null` when nothing is found. If
  `null` can be a real value in your array, check the key instead with
  `array_find_key()` or `array_key_first()`.
- The callbacks for `array_find()`, `array_any()` and `array_all()` receive
  the value and the key.
- `json_validate()` does not build the decoded value, so it uses less memory
  than `json_decode()` for a plain yes or no.
- `reset()` and `end()` need a variable and move the array's internal pointer.
  `array_first()` and `array_last()` work on any expression and touch nothing.

## Run the examples

This project uses PHP 8.5. Each function needs the PHP version in the table.
Run these commands from the repository root after `composer install`.

### [01-array-find-any-all.php](examples/01-array-find-any-all.php)

Finds the first non-admin user and its key, checks if any or all users are
adults and uses the key in a callback. The last part shows the older
`array_filter()` versions.

```shell
php ./018-array-and-string-functions/examples/01-array-find-any-all.php
```

### [02-array-first-and-last.php](examples/02-array-first-and-last.php)

Reads the first and last score with the PHP 8.5 functions, then with
`array_key_first()`, `array_key_last()`, `reset()` and `end()`. On an empty
array, `reset()` returns `false`.

```shell
php ./018-array-and-string-functions/examples/02-array-first-and-last.php
```

### [03-array-is-list.php](examples/03-array-is-list.php)

Checks five arrays. A gap after `unset()` or keys in the wrong order mean it
is not a list. That is exactly what decides whether `json_encode()` writes a
JSON array or an object.

```shell
php ./018-array-and-string-functions/examples/03-array-is-list.php
```

### [04-json-validate.php](examples/04-json-validate.php)

Validates six strings. Unquoted keys, trailing commas and an empty string are
invalid. `json_last_error_msg()` still explains why.

```shell
php ./018-array-and-string-functions/examples/04-json-validate.php
```

### [05-mb-trim.php](examples/05-mb-trim.php)

`trim()` leaves a no-break space and an ideographic space in place.
`mb_trim()` removes them. It also trims custom multibyte characters, and
`mb_ucfirst()` capitalizes the Lithuanian word "ąžuolas" where `ucfirst()`
cannot.

```shell
php ./018-array-and-string-functions/examples/05-mb-trim.php
```

The PHP manual has more on [array functions](https://www.php.net/manual/en/ref.array.php)
and [multibyte string functions](https://www.php.net/manual/en/ref.mbstring.php).
