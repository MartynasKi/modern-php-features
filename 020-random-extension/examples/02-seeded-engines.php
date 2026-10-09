<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Seeded engines');

use Random\Engine\Mt19937;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

function rolls(Randomizer $randomizer): string
{
    return implode(' ', array_map(fn() => $randomizer->getInt(1, 6), range(1, 8)));
}

section('Same seed, same sequence');
text(rolls(new Randomizer(new Xoshiro256StarStar(42))));
text(rolls(new Randomizer(new Xoshiro256StarStar(42))));

section('Each engine has its own sequence');
text('Mt19937: ' . rolls(new Randomizer(new Mt19937(42))));
text('PcgOneseq128XslRr64: ' . rolls(new Randomizer(new PcgOneseq128XslRr64(42))));

section('mt_rand() has one global state');
mt_srand(42);
$expected = mt_rand(1, 100);

mt_srand(42);
// Any other code that calls mt_rand() or rand() moves the same state.
rand();
text("Expected {$expected}, got " . mt_rand(1, 100));

section('A Randomizer keeps its own state');
$expected = new Randomizer(new Mt19937(42))->getInt(1, 100);

$randomizer = new Randomizer(new Mt19937(42));
rand();
text("Expected {$expected}, got " . $randomizer->getInt(1, 100));
