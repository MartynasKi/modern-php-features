# 006: Constructor Promotion and Readonly

Less boilerplate for small value objects and a way to make sure their values
never change.

## How constructor promotion works

Constructor promotion arrived in PHP 8.0. Add a visibility keyword such as
`private` to a constructor parameter and PHP declares the property and assigns
it for you. The property, the parameter and the assignment become one line.

Promoted parameters can have defaults and you can mix them with normal
parameters. They cannot be `callable` or variadic.

## How readonly works

Readonly properties arrived in PHP 8.1 and readonly classes in PHP 8.2.

- A readonly property can be set once, from inside the class or, since
  PHP 8.4, a child class. Any later write throws an `Error`, even from inside
  the class.
- It must have a type, because PHP needs an uninitialized state to tell
  "not set yet" from "set to null".
- A readonly class makes every property readonly and forbids dynamic
  properties.
- Readonly is shallow. If the property holds an object, that object can still
  change.

The rule is simple: once initialized, the property is locked. PHP does not
track who set it or why, so there is no "set it once more" exception.

PHP 8.6 adds default values for readonly properties. See
[999: Upcoming Features](../999-upcoming-features/README.md).

## The clone problem

Immutable objects usually have withers such as `withAmount()` that return a
changed copy. The natural way is `clone $this` and then change one property.
With readonly that fails, because `clone` copies the property in its
initialized state.

- Before PHP 8.3, the workaround was `new static(...)` with every argument
  repeated. It breaks when the constructor changes.
- PHP 8.3 lets `__clone()` set readonly properties again. That fixes deep
  cloning, but `__clone()` takes no arguments, so it cannot set a new value
  from the caller.
- PHP 8.5 adds `clone($object, ['amount' => 500])`, which finally makes withers
  short. See [016: Clone With](../016-clone-with/README.md).

## Run the examples

This project uses PHP 8.5. Promotion works since PHP 8.0, readonly properties
since PHP 8.1 and readonly classes since PHP 8.2. Run these commands from the
repository root after `composer install`.

### [01-promoted-properties.php](examples/01-promoted-properties.php)

`OldPoint` and `Point` dump the same object. `Point` gets there with a promoted
constructor and an empty body.

```shell
php ./006-constructor-promotion-and-readonly/examples/01-promoted-properties.php
```

### [02-readonly-properties.php](examples/02-readonly-properties.php)

Writing to `$email` fails from outside and from inside `changeEmail()`. The
`$tags` property is readonly too, but the `ArrayObject` inside it can still get
a new item.

```shell
php ./006-constructor-promotion-and-readonly/examples/02-readonly-properties.php
```

### [03-readonly-classes.php](examples/03-readonly-classes.php)

`Money` is a readonly class. Its properties cannot change and adding a dynamic
property throws an `Error`.

```shell
php ./006-constructor-promotion-and-readonly/examples/03-readonly-classes.php
```

### [04-clone-problem.php](examples/04-clone-problem.php)

`withAmountByClone()` fails because the copy is already initialized.
`withAmount()` works by repeating every constructor argument. The last part
uses the PHP 8.3 `__clone()` rule to give each `Invoice` its own `DateTime`.

```shell
php ./006-constructor-promotion-and-readonly/examples/04-clone-problem.php
```

The PHP manual has more on [constructor promotion](https://www.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor.promotion)
and [readonly properties](https://www.php.net/manual/en/language.oop5.properties.php#language.oop5.properties.readonly-properties).
