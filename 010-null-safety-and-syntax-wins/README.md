# 010: Null Safety and Small Syntax Wins

A handful of small features that remove boilerplate around `null`, default
objects and exceptions.

## Nullsafe operator

The nullsafe operator `?->` arrived in PHP 8.0. If the left side is `null`,
the whole chain stops and returns `null`. Otherwise it works like `->`.

```php
$city = $user?->address()?->city;
```

- It short-circuits. Once it hits `null`, the rest of the chain does not run,
  including function calls in the arguments.
- It only reads. You cannot write to it, as in `$user?->name = 'Ann'`.
- Unlike `??`, it does not hide undefined properties or variables.

## new in initializers

Since PHP 8.1, you can use `new` in parameter defaults, attribute arguments,
static variable initializers and global constants. The object is only created
when the default is actually used.

```php
public function __construct(private Logger $logger = new NullLogger()) {}
```

It does not work for property defaults or class constants. Use constructor
promotion for those.

## new without extra parentheses

Since PHP 8.4, you can call a method, read a property or read a constant on a
new object without wrapping it in parentheses. You still need `()` after the
class name.

```php
new Greeter()->greet();    // PHP 8.4
(new Greeter())->greet();  // before
```

## throw as an expression

Since PHP 8.0, `throw` is an expression, so it fits where a value is
expected: after `??` and `?:`, in a ternary and in an arrow function.

```php
$key = $config['APP_KEY'] ?? throw new InvalidArgumentException('Missing APP_KEY');
```

## Run the examples

This project uses PHP 8.5. Each script needs the PHP version listed above for
its feature. Run these commands from the repository root after
`composer install`.

### [01-nullsafe-operator.php](examples/01-nullsafe-operator.php)

Reads a city through a `User` with an address, a `User` without one and
`null`. The old style needs nested checks. The last part shows that
`load('arguments')` never runs because the chain stops at `$nobody`.

```shell
php ./010-null-safety-and-syntax-wins/examples/01-nullsafe-operator.php
```

### [02-new-in-initializers.php](examples/02-new-in-initializers.php)

`Mailer` defaults to a `NullLogger`, so the first call prints nothing and the
second one logs through an `EchoLogger`. Then a global constant and a static
variable hold objects created with `new`.

```shell
php ./010-null-safety-and-syntax-wins/examples/02-new-in-initializers.php
```

### [03-new-without-parentheses.php](examples/03-new-without-parentheses.php)

Calls a method, reads a property and reads a constant straight on
`new Greeter()`. An anonymous class works the same way.

```shell
php ./010-null-safety-and-syntax-wins/examples/03-new-without-parentheses.php
```

### [04-throw-expression.php](examples/04-throw-expression.php)

`env()` returns a config value or throws in one line. The other parts throw
from `?:` and from an arrow function.

```shell
php ./010-null-safety-and-syntax-wins/examples/04-throw-expression.php
```

The PHP manual has more on the [nullsafe operator](https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.nullsafe)
and [new](https://www.php.net/manual/en/language.oop5.basic.php#language.oop5.basic.new).
