# 012: Asymmetric Visibility

A property can have one visibility for reading and another for writing.

## How it works

Asymmetric visibility arrived in PHP 8.4.

```php
public private(set) int $balance = 0;
```

- The first keyword controls reading. The one with `(set)` controls writing.
- `private(set)` lets only the class write. `protected(set)` also lets child
  classes write.
- The write visibility cannot be wider than the read visibility.
- The property needs a type.
- `private(set)` on its own is short for `public private(set)`.

Before PHP 8.4, the usual pattern was a private property and a public getter
such as `balance()`. Now the property itself is the public API and only the
class can change it.

## Compared to readonly

Both block writes from outside, but they answer different questions.

- `readonly` means the value is set once and never changes, not even inside
  the class. Good for IDs and value objects.
- `private(set)` and `protected(set)` mean only the class decides when the
  value changes, as many times as it needs. Good for state such as a balance,
  a status or a counter.

Since PHP 8.4, a `readonly` property is `protected(set)` by default. You can
combine them, as in `public private(set) readonly int $id`, to also stop
children from setting it.

A rule of thumb: if the value never changes after construction, use
`readonly`. If the class should change it through its own methods, use
asymmetric visibility.

## Run the examples

This project uses PHP 8.5, but asymmetric visibility works since PHP 8.4. Run
these commands from the repository root after `composer install`.

### [01-public-private-set.php](examples/01-public-private-set.php)

`BankAccount` exposes `$balance` for reading. `deposit()` and `withdraw()` can
change it, but setting it from outside throws an `Error`.

```shell
php ./012-asymmetric-visibility/examples/01-public-private-set.php
```

### [02-compared-to-readonly.php](examples/02-compared-to-readonly.php)

`Order` has a readonly `$id` and a `protected(set)` `$status`. Both `Order`
and its child `PriorityOrder` can change the status many times. `changeId()`
fails because readonly allows only one write. From outside, both properties
are blocked.

```shell
php ./012-asymmetric-visibility/examples/02-compared-to-readonly.php
```

The PHP manual has more on [asymmetric property visibility](https://www.php.net/manual/en/language.oop5.visibility.php#language.oop5.visibility-members-aviz).
