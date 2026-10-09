# 020: Random Extension

The Random extension gives PHP an object-oriented random API with engines you
can choose, seed and pass around.

## How it works

The Random extension arrived in PHP 8.2. It has two parts:

- An engine produces raw random bytes. `Secure` uses the operating system's
  secure source and cannot be seeded. `Mt19937`, `PcgOneseq128XslRr64` and
  `Xoshiro256StarStar` are seedable and give the same sequence for the same
  seed.
- `Random\Randomizer` turns those bytes into useful values: `getInt()`,
  `shuffleArray()`, `pickArrayKeys()`, `shuffleBytes()` and `getBytes()`.
  PHP 8.3 added `getFloat()`, `nextFloat()` and `getBytesFromString()`.

`new Randomizer()` without an engine uses `Secure`.

## Secure vs deterministic

- Use the secure engine for anything an attacker should not guess: tokens,
  passwords, invite codes and IDs.
- Use a seeded engine when you want to repeat a result: tests, simulations,
  procedural content and replaying a bug.

The seeded engines are not secure. If someone learns the seed or enough
output, they can predict the rest.

## Why it matters for tests

The older functions `mt_rand()`, `rand()`, `shuffle()` and `array_rand()` all
share one hidden global state. `mt_srand(42)` in a test is fragile, because
any other code that uses one of those functions moves the same state.

A `Randomizer` keeps its own state. Accept one as a parameter, default to the
secure one in real code and pass a seeded one in tests. The test can then
assert an exact result.

## Run the examples

This project uses PHP 8.5, but the Random extension works since PHP 8.2.
`getFloat()` and `getBytesFromString()` need PHP 8.3. Run these commands from
the repository root after `composer install`.

### [01-randomizer.php](examples/01-randomizer.php)

A tour of `Randomizer` methods with the default secure engine. The output
changes on every run.

```shell
php ./020-random-extension/examples/01-randomizer.php
```

### [02-seeded-engines.php](examples/02-seeded-engines.php)

Two `Xoshiro256StarStar` engines with seed `42` roll the same dice. Other
engines with the same seed give other sequences. Then a stray `rand()` call
breaks a seeded `mt_rand()` sequence but does not touch a `Randomizer`.

```shell
php ./020-random-extension/examples/02-seeded-engines.php
```

### [03-secure-vs-deterministic.php](examples/03-secure-vs-deterministic.php)

`pickWinner()` takes an optional `Randomizer` that defaults to the secure
engine. `resetToken()` always uses a secure one by default. With a seeded
engine, the winner is `Bob` on every run, so a test can check it.

```shell
php ./020-random-extension/examples/03-secure-vs-deterministic.php
```

The PHP manual has more on the [Random extension](https://www.php.net/manual/en/book.random.php).
