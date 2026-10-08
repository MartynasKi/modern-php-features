<?php

declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

title('References vs copies');

section('Copy');
$a = 1;
$b = $a;
$b = 2;
dump(['$a' => $a, '$b' => $b]);

section('Reference');
$a = 1;
$b = &$a;
$b = 2;
dump(['$a' => $a, '$b' => $b]);

section('The foreach gotcha');
$numbers = [1, 2, 3];

foreach ($numbers as &$number) {
    $number *= 2;
}

// $number still points to the last element, so this loop overwrites it.
foreach ($numbers as $number) {
}

dump($numbers);
