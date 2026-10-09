# Modern PHP Features

First of all... Hello!

I am Martynas Ki. 👋 And nice to meet you!

Even after more than a decade of coding, there is always something new to learn or something old to remember.

From time to time, I share what I discover on my [Laraholic blog](https://laraholic.com/), where I write about Laravel, PHP or whatever else 
catches my interest.

So... here I am, revisiting modern PHP and refreshing my understanding of how things *really* work under the hood. 🤓

## Requirements

- [PHP 8.5](https://www.php.net/downloads.php), the latest stable PHP series
- [Composer](https://getcomposer.org/download/)

## How to run

1. Clone the repository.

   ```shell
   git clone https://github.com/MartynasKi/modern-php-features.git
   cd modern-php-features
   ```

2. Install dependencies.

   ```shell
   composer install
   ```

3. Run an example.

   ```shell
   php ./002-fibers/examples/01-basic-fiber.php
   ```

See each topic README for explanations and example commands.

## PHP topics to explore

- [001: Fundamentals](001-fundamentals/README.md): how PHP runs your code,
  OPcache and JIT, how values live in memory, strict types and type juggling.
- [002: Fibers](002-fibers/README.md): pause and resume a function, pass values
  in and out, read its return value and run several fibers in a loop.
- [003: Generators](003-generators/README.md): yield values and keys, send
  values in, delegate with yield from, save memory and compare with fibers.
- [004: Enums](004-enums/README.md): pure and backed enums, methods, interfaces,
  constants, from() vs tryFrom(), match and JSON.
- [005: Match and Named Arguments](005-match-and-named-arguments/README.md):
  match vs switch, UnhandledMatchError and named arguments with defaults.
- [006: Constructor Promotion and Readonly](006-constructor-promotion-and-readonly/README.md):
  promoted properties, readonly properties and classes and the clone problem.
- [007: Attributes and Reflection](007-attributes-and-reflection/README.md):
  custom attributes, reading them with reflection and a tiny router.
- [008: Union, Intersection and DNF Types](008-union-intersection-dnf-types/README.md):
  combined types, standalone null, false and true, never and static.
- [009: First-Class Callable Syntax](009-first-class-callables/README.md):
  strlen(...) and how it compares to string callables.
- [010: Null Safety and Small Syntax Wins](010-null-safety-and-syntax-wins/README.md):
  ?->, new in initializers, new without parentheses and throw expressions.
- [011: Property Hooks](011-property-hooks/README.md): get and set hooks,
  virtual properties and hooks on interfaces.
- [012: Asymmetric Visibility](012-asymmetric-visibility/README.md):
  public private(set) and when to pick it over readonly.
- [013: Lazy Objects](013-lazy-objects/README.md): lazy ghosts and proxies, the
  way ORMs and containers delay work.
- [014: Weak References](014-weak-references/README.md): WeakReference,
  WeakMap and a cache that does not leak.
- [015: Pipe Operator](015-pipe-operator/README.md): |> chains and what can go
  on the right side.
- [016: Clone With](016-clone-with/README.md): clone with new property values
  and short withers on readonly classes.
- [017: NoDiscard, Override and Deprecated](017-nodiscard-override-deprecated/README.md):
  attributes that make the engine catch mistakes.
- [018: New Array and String Functions](018-array-and-string-functions/README.md):
  array_find(), array_first(), json_validate(), mb_trim() and friends.
- [019: Typed Class Constants](019-typed-class-constants/README.md): typed and
  final constants, dynamic fetch and constants in enums.
- [020: Random Extension](020-random-extension/README.md): Randomizer, seeded
  engines and why it matters for tests.
- [021: URI Extension](021-uri-extension/README.md): parse and modify URLs and
  why parse_url() is not enough.
- [022: New DOM API](022-dom-api/README.md): HTML5 parsing and CSS selectors
  compared to DOMDocument.
- [023: Errors and Deprecations](023-errors-and-deprecations/README.md): the
  Throwable hierarchy, TypeError, ValueError and fixing deprecations.
- [998: Tips and Tricks](998-tips-and-tricks/README.md): built-in functions as
  callables, hrtime(), array tricks, division and JSON flags.

---

This is a personal learning project, not an authoritative reference.

If you spot a mistake or have a suggestion, please let me know.
