# 016: Clone With

`clone($object, [...])` copies an object and changes some of its properties
in one step.

## How it works

Clone with arrived in PHP 8.5. `clone` can now be called like a function with
an optional second argument: an array of property names and new values.

```php
$copy = clone($settings, ['theme' => 'dark']);
```

- PHP clones the object, runs `__clone()` and then assigns the new values.
- Assignments follow the normal rules: visibility, readonly, `set` hooks and
  types all apply.
- The old `clone $object` syntax still works.

Two details worth knowing:

- Values are converted like in non-strict mode, even with
  `declare(strict_types=1)`. The string `'16'` becomes the int `16`. A string
  such as `'big'` still throws a `TypeError`.
- You cannot chain on the call, as in `clone($a, [...])->theme`. Assign it to
  a variable first.

## Withers on readonly classes

This is the fix for the clone problem from
[006: Constructor Promotion and Readonly](../006-constructor-promotion-and-readonly/README.md).
Inside the class, a wither now changes only the property it is about:

```php
public function withAmount(int $amount): static
{
    return clone($this, ['amount' => $amount]);
}
```

Before PHP 8.5, every wither had to call `new static(...)` with every
constructor argument repeated. Adding a property meant updating every wither.

Readonly properties are `protected(set)` by default, so `clone()` from outside
the class cannot change them. Withers are still the public API. If you want
outside code to use `clone()` directly, declare the property as
`public public(set) readonly`.

## Run the examples

This project uses PHP 8.5, which is also the version that introduced clone
with. Run these commands from the repository root after `composer install`.

### [01-clone-with.php](examples/01-clone-with.php)

Copies `Settings` with a new theme and language and leaves the original
alone. Then it shows that `'16'` is converted to an int and `'big'` throws.

```shell
php ./016-clone-with/examples/01-clone-with.php
```

### [02-withers-on-readonly-classes.php](examples/02-withers-on-readonly-classes.php)

`Money` is a readonly class with two short withers that chain nicely. The
original object stays the same. Calling `clone()` with `amount` from outside
the class throws an `Error`.

```shell
php ./016-clone-with/examples/02-withers-on-readonly-classes.php
```

### [03-before-and-after.php](examples/03-before-and-after.php)

The same `withCity()` wither twice. `OldAddress` repeats every argument.
`Address` names only `city`.

```shell
php ./016-clone-with/examples/03-before-and-after.php
```

The PHP manual has more on [object cloning](https://www.php.net/manual/en/language.oop5.cloning.php).
