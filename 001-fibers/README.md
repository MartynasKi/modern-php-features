# 001: Fibers

I wanted to see what happens when a function pauses and lets the calling code
take over. These three examples follow that flow, then look at passing values
and reading the final result.

## How fibers work

Fibers arrived in PHP 8.1. Each fiber has its own call stack. When it pauses,
PHP keeps its local variables and execution state so it can continue later.
It can even pause from a function called inside the fiber.

Creating a `Fiber` stores the callback without running it. `start()` begins
execution and passes its arguments to that callback. `Fiber::suspend()` pauses
the fiber and hands control back to the code that started or resumed it.

`resume()` continues a suspended fiber. Its argument becomes the value returned
by `suspend()` inside the fiber. Once the callback returns, `getReturn()` reads
the final result. Calling it before completion throws `FiberError`.

Fibers are useful for cooperative task scheduling and asynchronous programming
tools. A scheduler can let one task pause while another makes progress.
Fibers are not threads and do not automatically run code in parallel.
A blocking call still blocks; fibers alone do not make I/O asynchronous.

## Run the examples

This project uses PHP 8.5 and Composer 2, even though fibers themselves have
been available since PHP 8.1. Run these commands from the repository root.

Install the shared tools first:

```shell
composer install
```

Each script loads `bootstrap.php` for SymfonyStyle output and VarDumper value
inspection. The scripts run independently and use native PHP fibers directly.

### 01-basic-fiber.php

Create a fiber, start it, pause it and resume it. The script dumps its state
before starting, while suspended and after completion. In that order, the
values of `isStarted()`, `isSuspended()` and `isTerminated()` are:

```text
Before start:     false, false, false
While suspended: true,  true,  false
After completion: true, false, true
```

The fiber also reads a local variable after resuming. Its value survives the
pause. Notice that `isStarted()` stays true after the fiber finishes.

```shell
php ./001-fibers/examples/01-basic-fiber.php
```

### 02-passing-values.php

Follow three values: `start('Martynas')` sends a name into the callback.
`suspend()` sends a question back as the result of `start()`.
`resume('PHP fibers')` sends the answer into the paused callback.

The dumps show `Martynas`, then `Martynas, what are you learning?` and finally
`PHP fibers`. This helped me separate the value leaving the fiber from the
value it receives when it continues.

```shell
php ./001-fibers/examples/02-passing-values.php
```

### 03-fiber-return-value.php

The fiber calculates `42` but pauses before returning it. The script tries
`getReturn()` while suspended and catches the expected `FiberError`.
The value exists inside the callback but is not yet its return value.

After resuming, `isTerminated()` becomes true and `getReturn()` returns `42`.
The result of `resume()` is `null` here: it does not carry the callback's
final return value.

```shell
php ./001-fibers/examples/03-fiber-return-value.php
```

The PHP manual has more on [fibers](https://www.php.net/manual/en/language.fibers.php),
[passing values through suspension](https://www.php.net/manual/en/fiber.suspend.php)
and [reading the final result](https://www.php.net/manual/en/fiber.getreturn.php).
