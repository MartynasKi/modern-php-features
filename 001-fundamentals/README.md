# 001: Fundamentals

Small PHP basics worth a second look, from how PHP runs a file to how it
stores values. Some subtopics have an example script and some are only notes.

Some of these matter a lot when you build or tune high-performance PHP apps,
especially OPcache, the JIT, copy-on-write and long-running runtimes.

This project uses PHP 8.5. Run the examples from the repository root after
`composer install`.

## How PHP runs your code

Every time PHP runs a file, it goes through the same pipeline:

```text
source code -> tokens -> AST -> opcodes -> Zend VM -> output
```

1. The lexer splits the source into tokens such as `$name`, `=` and `'PHP'`.
2. The parser turns the tokens into an abstract syntax tree (AST).
3. The compiler turns the AST into opcodes. Opcodes are PHP's bytecode: small
   instructions for the engine.
4. The Zend VM executes the opcodes one by one. Each opcode has a handler
   written in C, so the VM interprets your code instead of turning it into
   machine code. Only the JIT compiles opcodes to machine code.

You can see the tokens yourself with `PhpToken::tokenize()`.

## Request lifecycle

PHP has two kinds of setup work: once per process and once per request.

- Per process: load extensions, read `php.ini` and set up shared memory such
  as OPcache.
- Per request: fill the superglobals, run the script and then free every
  variable, object and resource it created.

How often each happens depends on the runtime: whatever starts PHP and
decides how long its process stays alive.

- CLI: one process runs one script and then exits.
- PHP-FPM: a pool of worker processes stays alive. Each worker handles many
  requests one at a time. It resets all script state after each one.
- Long-running runtimes such as FrankenPHP worker mode, RoadRunner, Swoole
  or Laravel Octane: the app boots once and serves many requests in the same
  running script.

So "state resets every request" is true for the CLI and PHP-FPM. In a
long-running runtime, static properties, globals and singletons survive
between requests. That makes things faster, but data can leak from one
request into the next if you are not careful.

## Opcodes

Opcodes are the instructions the Zend VM actually runs. Looking at them shows
what your code costs and how two ways of writing the same thing differ.

OPcache can print them for you. It must be turned on for the CLI first:

```shell
php -d opcache.enable_cli=1 -d opcache.opt_debug_level=0x10000 ./001-fundamentals/examples/01-opcodes.php
```

`0x10000` prints the opcodes before optimization and `0x20000` prints them
after. If you use Laravel Herd, also add `-d auto_prepend_file=` because Herd
loads one of its own files before every script. Other tools for this are
`phpdbg -p*` and the VLD extension.

### [01-opcodes.php](examples/01-opcodes.php)

The script prints the same greeting twice. Concatenation with `.` compiles to
two `CONCAT` opcodes. Interpolation with `"Hello {$name}\n"` compiles to
`ROPE_INIT`, `ROPE_ADD` and `ROPE_END`, which build the string in one go.
This script skips [bootstrap.php](../bootstrap.php) so the dump only shows this file.

## OPcache

Without OPcache, PHP repeats the whole pipeline for every file on every
request. OPcache stores the compiled opcodes in shared memory instead.

First request for a file (cache miss):

```text
source file -> tokens -> AST -> opcodes -> save in OPcache -> Zend VM -> output
```

Later requests for the same file (cache hit):

```text
OPcache lookup -> opcodes from shared memory -> Zend VM -> output
```

Since PHP 8.5, OPcache is always built into PHP. Common settings:

- `opcache.enable`: on by default for web requests.
- `opcache.enable_cli`: off by default, because a CLI process exits after one
  script and would throw the cache away anyway.
- `opcache.memory_consumption`: how much shared memory the cache can use.
- `opcache.max_accelerated_files`: how many files fit in the cache.
- `opcache.validate_timestamps`: checks files for changes. Production setups
  often turn it off and clear the cache on deploy.

To check whether it is on, run `php --ri "Zend OPcache"` or call
`opcache_get_status()`, which returns `false` when OPcache is off. The effect
shows on repeated web requests, so measure it by comparing requests per second
with OPcache on and off.

## Preloading

Preloading arrived in PHP 7.4. The `opcache.preload` setting points to a
script that runs once when the server starts. Every class and function it
loads stays in memory for all later requests.

It can help large frameworks with stable code. The trade-offs: any change to
preloaded code needs a server restart, it uses memory for code you may not
need and it does not work on Windows. For most apps, plain OPcache already
gives most of the gain.

## JIT

The JIT arrived in PHP 8.0 as part of OPcache. It watches which opcodes run
often and compiles them into machine code for your CPU.

```text
source code -> opcodes -> JIT -> native machine code -> CPU
```

There is one important detail: the JIT does not replace the Zend VM. Code
runs in the VM until the JIT decides it is hot enough to compile. Even
compiled code jumps back to the VM for operations the JIT does not handle
itself.

This helps code that spends its time inside PHP itself, such as math loops.
A typical web app spends most of its time waiting for the database or the
network. It also spends time inside functions like `md5()` that are already
written in C. **The JIT cannot speed up either of those.**

Since PHP 8.4, the JIT is turned off with `opcache.jit=disable` by default.
Turn it on with `opcache.jit=tracing`.

### [02-jit.php](examples/02-jit.php)

The script times two functions. `math()` runs a plain arithmetic loop.
`strings()` mostly calls `str_repeat()`, `md5()` and `strlen()`. Run it
without and with the JIT:

```shell
php ./001-fundamentals/examples/02-jit.php
php -d opcache.enable_cli=1 -d opcache.jit=tracing ./001-fundamentals/examples/02-jit.php
```

In a test run, `math()` dropped from about 600 ms to 35 ms. `strings()` only
went from about 110 ms to 90 ms. Your numbers will differ, but the gap between
the two should stay.

## Values and zvals

PHP stores every value in a small container called a zval. A zval has two
parts: a type tag saying what kind of value it is and a payload with the
value itself.

Small values such as integers, floats, booleans and `null` fit straight into
the payload. Bigger values such as strings, arrays and objects live somewhere
else in memory and the payload points to them.

This is why PHP is dynamically typed. Variables do not have types. The values
inside them do, and the type tag can change whenever a new value is assigned.

## Refcounting

Strings, arrays and objects keep a counter of how many places use them.
Assigning the value to another variable adds one. `unset()` or leaving a
function scope removes one. When the count reaches zero, PHP frees the memory
right away.

`debug_zval_dump()` shows the counter as `refcount`.

### [03-refcounting.php](examples/03-refcounting.php)

The object starts at `refcount(2)`, goes to `refcount(3)` after
`$second = $first` and back to `refcount(2)` after `unset($second)`. Each count
is one higher than you might expect, because `debug_zval_dump()` holds the
value too while it prints.

```shell
php ./001-fundamentals/examples/03-refcounting.php
```

## Copy-on-write

Assigning an array to another variable does not copy it. Both variables share
the same array and the refcount goes up. PHP only makes a real copy when one
of them is changed.

### [04-copy-on-write.php](examples/04-copy-on-write.php)

The script compares memory use with the start after each step. Creating
100,000 numbers takes 4 MB. After `$copy = $original` it is still 4 MB, because
both variables share one array. Adding one element to `$copy` makes PHP copy
the whole array, so it jumps to 8 MB.

```shell
php ./001-fundamentals/examples/04-copy-on-write.php
```

## References vs copies

`$b = $a` gives `$b` its own value. `$b = &$a` makes both names point to the
same value, so changing one changes the other.

The classic gotcha: after `foreach ($numbers as &$number)`, `$number` is still
a reference to the last element. Any later write to `$number`, such as a
second loop that reuses the name, changes that element. Call `unset($number)`
after a by-reference loop to avoid it.

### [05-references.php](examples/05-references.php)

The copy leaves `$a` at `1` and the reference changes it to `2`. Then the
foreach gotcha: one `$number = 1_000;` after the loop turns `[2, 4, 6]` into
`[2, 4, 1000]`.

```shell
php ./001-fundamentals/examples/05-references.php
```

## Strict types

`declare(strict_types=1);` arrived in PHP 7.0. It goes at the very top of a
file.

It only changes one thing: scalar type declarations for function arguments
and return values. Without it, PHP quietly converts values, so passing `'5'` to
an `int` parameter becomes `5`. With it, the same call throws a `TypeError`.
An `int` is still accepted where a `float` is expected.

It applies per file. For arguments, the file making the call decides. A strict
file calling a function from a non-strict file is still checked strictly.
For return values, the file where the function is defined decides.

Operators such as `+` and comparisons such as `==` still convert types as
before.

### [06-strict-types.php](examples/06-strict-types.php)

`double(5)` and `half(5)` work. `double('5')` throws a `TypeError` and so does
the built-in `strlen(123)`. `'5' + 1` and `'5' == 5` still work.

```shell
php ./001-fundamentals/examples/06-strict-types.php
```

## Type juggling

`==` converts both sides to a common type before comparing. This causes most
of the surprises.

PHP 8 fixed the worst one. Comparing a number with a non-numeric string now
compares them as strings. **So `0 == 'a'` is `false` in PHP 8, but it was `true`
in PHP 7.** Numeric strings are still compared as numbers, so `'1' == '01'` and
`'10' == '1e1'` are `true`.

Rule of thumb: use `===` by default. It compares both the type and the value.

### [07-type-juggling.php](examples/07-type-juggling.php)

The script dumps a few loose comparisons that are all `true` except
`0 == 'a'`. Then it shows `===` returning `false` for the same kind of cases.

```shell
php ./001-fundamentals/examples/07-type-juggling.php
```

## Static variables

A `static` variable inside a function keeps its value between calls.
It is created on the first call and stays local to that function.

```php
function counter(): int
{
    static $count = 0;

    return ++$count;
}
```

Since PHP 8.3, the initial value can be any expression, such as a function
call. Before that, it had to be a constant value.

The `io()` helper in [bootstrap.php](../bootstrap.php) uses this to create the
SymfonyStyle object only once:

```php
function io(): SymfonyStyle
{
    static $io;

    return $io ??= new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
}
```

The first call creates the object. Every later call returns the same one.

### [08-static-variables.php](examples/08-static-variables.php)

Call `counter()` three times and dump the results: `1`, `2` and `3`.

```shell
php ./001-fundamentals/examples/08-static-variables.php
```

## Memory and performance habits

Copy-on-write means passing an array to a function is cheap. PHP only copies
it if the function changes it. Using `&` to "save memory" does not help and
can even force an extra copy.

Objects work a bit differently. A variable does not hold the object itself,
only a handle that points to it. Assigning or passing an object copies the
handle, so both variables work with the same object:

```php
$a = new stdClass();
$b = $a;
$b->name = 'PHP';

echo $a->name; // PHP
```

Use `clone` when you need a separate object. When you want to know what
something really costs, measure it with `memory_get_usage()`,
`memory_get_peak_usage()` and `hrtime()` instead of guessing.

## Ways to look inside PHP yourself

- `php -v` shows the version and loaded engine extensions such as OPcache.
- `php --ini` shows which `php.ini` files are loaded.
- `php -i` prints the full configuration, like `phpinfo()`.
- `php -m` lists the loaded extensions.
- `php --ri "Zend OPcache"` shows the settings of one extension.
- `php -d key=value` overrides a setting for one run.
- `opcache_get_status()` shows what OPcache has cached and whether the JIT is on.
- `memory_get_usage()` and `memory_get_peak_usage()` show current and peak memory.
- `debug_zval_dump()` shows refcounts.
- `phpdbg` can step through code and print opcodes with `phpdbg -p*`.

The PHP manual has more on [OPcache](https://www.php.net/manual/en/book.opcache.php),
[references](https://www.php.net/manual/en/language.references.php) and
[type juggling](https://www.php.net/manual/en/language.types.type-juggling.php).
