<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Secure vs deterministic');

use Random\Engine\Secure;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

// The default is new in an initializer, so real code gets the secure engine.
function pickWinner(array $names, Randomizer $randomizer = new Randomizer()): string
{
    return $names[$randomizer->pickArrayKeys($names, 1)[0]];
}

function resetToken(Randomizer $randomizer = new Randomizer(new Secure())): string
{
    return bin2hex($randomizer->getBytes(16));
}

$names = ['Ann', 'Bob', 'Cid', 'Dan'];

section('Real code: different on every run');
dump(pickWinner($names));
dump(resetToken());

section('Test: a seeded engine gives the same winner every run');
dump(pickWinner($names, new Randomizer(new Xoshiro256StarStar(2026))));
dump(pickWinner($names, new Randomizer(new Xoshiro256StarStar(2026))) === 'Bob');
