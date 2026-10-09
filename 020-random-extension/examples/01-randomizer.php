<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Random\Randomizer');

use Random\IntervalBoundary;
use Random\Randomizer;

// Without an engine, the Randomizer uses the secure engine.
$randomizer = new Randomizer();

section('Numbers');
dump($randomizer->getInt(1, 6));
dump($randomizer->getFloat(0, 1, IntervalBoundary::ClosedOpen));

section('Arrays');
dump($randomizer->shuffleArray(['ann', 'bob', 'cid']));
dump($randomizer->pickArrayKeys(['red' => 1, 'green' => 2, 'blue' => 3], 2));

section('Strings');
dump($randomizer->shuffleBytes('abcdef'));
dump($randomizer->getBytesFromString('0123456789ABCDEF', 8));
dump(bin2hex($randomizer->getBytes(4)));
