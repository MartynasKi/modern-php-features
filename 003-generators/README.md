# 003: Generators

A generator is a function that hands out values one at a time instead of
building a whole array first.

## How generators work

Generators arrived in PHP 5.5. Any function that contains `yield` returns a
`Generator` object instead of running its body.

- Calling the function runs nothing. The body starts when you first ask for a
  value, for example with `foreach` or `current()`.
- `yield $value` hands out a value and pauses the function. Its local
  variables stay alive until the next value is needed.
- `yield $key => $value` sets a key. Without one, PHP counts up from 0.
- `send($value)` resumes the generator and becomes the result of the paused
  `yield` expression.
- `yield from` hands out every value from another generator, array or
  `Traversable`.
- `return` sets the final value. Read it with `getReturn()`.

Generators are great for big files, database cursors and long lists that do
not fit in memory. They can only be iterated once and cannot be rewound after
they start.

## Run the examples

This project uses PHP 8.5. Run these commands from the repository root after
`composer install`.

### [01-yield-basics.php](examples/01-yield-basics.php)

Calling `countdown()` returns a `Generator` but prints nothing. The body only
starts when `foreach` asks for the first value. Without keys, the values are
numbered 0, 1, 2 like a list.

The second part yields custom keys. The same key can appear twice.
`iterator_to_array()` runs the whole generator and collects it into an array,
so a repeated key keeps only its last value.

```shell
php ./003-generators/examples/01-yield-basics.php
```

### [02-send.php](examples/02-send.php)

A running total. `yield` works both ways: it sends the total out and pauses,
then receives the value passed to `send()` as `$amount`. `current()` runs the
generator to its first `yield`. Each `send()` pushes an amount in, the loop
adds it and the next `yield` hands the new total back.

```shell
php ./003-generators/examples/02-send.php
```

### [03-yield-from.php](examples/03-yield-from.php)

`everything()` delegates to two generators and an array. The inner sources
keep their own keys, so `0` and `1` show up three times. Pass
`preserve_keys: false` to `iterator_to_array()` to keep every value.

```shell
php ./003-generators/examples/03-yield-from.php
```

### [04-return-value.php](examples/04-return-value.php)

`importRows()` yields each row and returns how many it imported. A
`yield from` expression evaluates to the inner generator's return value, so
`importBatches()` can add up the batch counts. Calling `getReturn()` before the
generator finishes throws an `Exception`.

```shell
php ./003-generators/examples/04-return-value.php
```

### [05-memory.php](examples/05-memory.php)

Sums one million numbers twice. The array version holds every number at once
and needs about 18 MB. The generator version only holds the current number and
needs under 1 KB. Exact numbers depend on your build.

```shell
php ./003-generators/examples/05-memory.php
```

## Generators vs fibers

Both can pause a function and continue later. The difference is where they
can pause.

- A generator can only pause in its own body. If a nested function needs to
  pause, it must be a generator too and every caller must use `yield from`.
- A [fiber](../002-fibers/README.md) has its own call stack. `Fiber::suspend()`
  works from any depth and the functions in between stay plain functions.

Use generators to produce a sequence of values. Use fibers when the code that
pauses should not need to know about it, as in async libraries.

### [06-generators-vs-fibers.php](examples/06-generators-vs-fibers.php)

The same two-step task written both ways. The generator version needs
`yield from` in `generatorTask()`. The fiber version calls `fiberStep()` like
a normal function and still pauses inside it.

```shell
php ./003-generators/examples/06-generators-vs-fibers.php
```

The PHP manual has more on [generators](https://www.php.net/manual/en/language.generators.php).
