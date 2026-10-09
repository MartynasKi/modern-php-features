<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('array_first and array_last');

$scores = ['ann' => 90, 'bob' => 75, 'cid' => 82];

section('PHP 8.5');
dump(array_first($scores));
dump(array_last($scores));
dump(array_first([]));

section('The keys came earlier, in PHP 7.3');
dump(array_key_first($scores));
dump(array_key_last($scores));

section('Before: reset() and end() move the internal pointer');
// They need a variable, because they take the array by reference.
dump(reset($scores));
dump(end($scores));

section('reset() on an empty array returns false, not null');
$empty = [];
dump(reset($empty));
