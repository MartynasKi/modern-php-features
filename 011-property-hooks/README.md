# 011: Property Hooks

Property hooks put get and set logic right on a property. Callers keep using
normal property syntax.

## How hooks work

Property hooks arrived in PHP 8.4.

- A `get` hook runs on every read. A `set` hook runs on every write and
  receives the new value as `$value`.
- Hooks run everywhere, including inside the class and the constructor. Only
  inside its own hook does `$this->email` touch the stored value directly.
- Short forms: `get => expression;` returns the expression and
  `set => expression;` stores it.
- A property whose hooks never use its own stored value is virtual. It takes
  no memory in the object. If it only has a `get` hook, writing to it throws.
- Interfaces and abstract classes can require properties, such as
  `public string $name { get; }`. A plain public property or a hook can
  fulfil it.

Hooks cannot be used on readonly properties. Writing to an element of an
array property with a `set` hook, as in `$object->items[] = 'x'`, throws an
`Error`. Assign the whole array instead.

## What hooks replace

- Getters and setters. You can start with a plain public property and add a
  hook later without changing any caller.
- Magic `__get()` and `__set()`. They catch every missing property in one
  method, lose types and hide properties from IDEs. A hook is a real typed
  property.

## Run the examples

This project uses PHP 8.5, but hooks work since PHP 8.4. Run these commands
from the repository root after `composer install`.

### [01-get-and-set-hooks.php](examples/01-get-and-set-hooks.php)

`User` cleans its email in a `set` hook and throws a `ValueError` for an email
without `@`. The constructor goes through the hook too. The `get` hook on
`$name` capitalizes it on read.

```shell
php ./011-property-hooks/examples/01-get-and-set-hooks.php
```

### [02-virtual-properties.php](examples/02-virtual-properties.php)

`$area` is computed from width and height on every read, so it follows any
change. Writing to it throws. `$size` has both hooks and splits `'5x6'` into
width and height. An array cast shows only `width` and `height`, because the
virtual properties store nothing.

```shell
php ./011-property-hooks/examples/02-virtual-properties.php
```

### [03-hooks-on-interfaces.php](examples/03-hooks-on-interfaces.php)

The `Shape` interface requires readable `$name` and `$area` properties.
`Square` fulfils `$name` with a plain property and `Circle` with a hook.

```shell
php ./011-property-hooks/examples/03-hooks-on-interfaces.php
```

### [04-what-they-replace.php](examples/04-what-they-replace.php)

The same product price three ways: getter and setter methods, magic
`__get()` and `__set()` and a hook. The magic version stores the string `'10'`
without complaint. The hook version keeps its `int` type and blocks negative
prices.

The script cuts the `TypeError` message at `, called in` to hide the long file
path.

```shell
php ./011-property-hooks/examples/04-what-they-replace.php
```

The PHP manual has more on [property hooks](https://www.php.net/manual/en/language.oop5.property-hooks.php).
