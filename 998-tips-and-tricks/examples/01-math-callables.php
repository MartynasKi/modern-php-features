<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('Built-in functions as callables');

$changes = [-3.7, 12.2, -0.4, 8.5];

section('Instead of fn($n) => abs($n)');
dump(array_map(abs(...), $changes));

section('round(...) and other math functions work the same way');
dump(array_map(round(...), $changes));

section('max(...) takes two values, so it fits array_reduce()');
dump(array_reduce($changes, max(...), -INF));

section('Sort strings with strcmp(...) or strnatcmp(...)');
$files = ['img12.png', 'img10.png', 'img2.png'];
usort($files, strcmp(...));
dump($files);
usort($files, strnatcmp(...));
dump($files);

section('Filter with is_numeric(...) and similar checks');
dump(array_filter(['10', 'abc', '3.5', ''], is_numeric(...)));
