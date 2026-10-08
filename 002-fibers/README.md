# 002: Fibers

A fiber is a function that can pause itself and continue later.

## How fibers work

Fibers arrived in PHP 8.1. Each fiber has its own call stack, so PHP keeps
its local variables while it is paused.

- `new Fiber($callback)` stores the callback but does not run it.
- `start()` runs the callback until it calls `Fiber::suspend()`.
- `Fiber::suspend()` pauses the fiber and gives control back to the caller.
- `resume()` continues the fiber from where it paused.
- `getReturn()` reads the value the callback returned.

Fibers are the building block for async libraries and task schedulers.
They are not threads. Only one fiber runs at a time and a blocking call
still blocks everything.

## Run the examples

This project uses PHP 8.5, but fibers work since PHP 8.1. Run these commands
from the repository root after `composer install`.

### 01-basic-fiber.php

Start a fiber, let it pause and then resume it. The output shows how control
jumps between the main code and the fiber.

```shell
php ./002-fibers/examples/01-basic-fiber.php
```

### 02-passing-values.php

Values travel both ways. `start()` sends a name in and gets back the question
passed to `suspend()`. `resume()` sends the answer in and `suspend()` returns it
inside the fiber.

```shell
php ./002-fibers/examples/02-passing-values.php
```

### 03-return-value.php

When the callback finishes, `getReturn()` gives you its return value. Here it
is `42`. Calling `getReturn()` before the fiber finishes throws `FiberError`.

```shell
php ./002-fibers/examples/03-return-value.php
```

### 04-foreach-loop.php

Two fibers share one callback, but each gets its own list of steps through
`start()`. Task A has three steps and Task B has five. A small loop resumes
each fiber in turn, so their steps take turns. When Task A finishes, it is
removed and Task B runs its last two steps alone.

The fibers do not print anything. Each step is passed to `suspend()` and the
main code prints it, a bit like `yield` in a generator. `start()` returns the
first value and each `resume()` returns the next one. The last `resume()`
returns `null` because the fiber finishes without suspending again.

```shell
php ./002-fibers/examples/04-foreach-loop.php
```

The PHP manual has more on [fibers](https://www.php.net/manual/en/language.fibers.php).
